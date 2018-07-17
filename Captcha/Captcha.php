<?php
namespace Captcha;

/**
 * 提供验证码服务
 *
 * 验证码有效时间默认10分钟
 *
 * <demo>
 * 发送短信验证码  使用openapi的短信服务来发送短信(注意：使用openapi的短信服务需要先在openapi平台上开通短信服务)
 * $captcha = new \Base\Captcha\Captcha();
 * $id = $captcha->create('digit', 6, 'id'); //6位数字验证码
 * try{
 *      $ret = $captcha->show('sms', array(
 *                                  'service' => 'openapi',
 *                                  'phone' => '12345678910',
 *                                  'template' => "验证码为%captcha%, 有效时间十分钟",
 *                                  'mode' => 'test' //openapi服务的短信发送模式 不赋值则使用默认发送模式(其他服务不需要此值)
 *                                  )
 *      );
 * } catch (\Base\Exception $e){
 *      //异常处理
 * }
 * template中得%captcha%的位置将会被替换成验证码
 *
 *
 * 获取图片验证码
 * $captcha = new \Captcha\Captcha();
 * $id = $captcha->create('mix', 4, 'id'); //4位的数字字母混合验证码
 * $captcha->show('image', array('width' => 80, 'height' => 30));
 * show方法将自动生成验证码图片并输出
 *
 * 验证
 * $ret = \Base\Captcha\Captcha::validate('123456', 'id');
 * 验证时不区分字母的大小写
 * </demo>
 *
 */
class Captcha {

    const TYPE_DIGIT = 'digit';   //数字验证码
    const TYPE_ENGLISH = 'english'; //英文类型验证码 大小写均有(验证时不限大小写)
    const TYPE_ENGLISH_LOW = 'english_low';  //小写英文验证码(验证时不限大小写)
    const TYPE_ENGLISH_HIGH = 'english_high'; //大写英文验证码(验证时不限大小写)
    const TYPE_MIX = 'mix'; //混合类型验证码 包括数字 大写字母 小写字母(验证时不限大小写)

    protected $code;

    /**
     * 生成验证码并存储
     *
     * @param string $type 验证码类型 支持类型见上
     * @param int    $length 验证码的长度
     * @param string $id 验证码ID 如果为null 将生成一个唯一ID并返回
     * @param string $prefix 验证码ID的前缀 如果提供的ID不能保证唯一性 请使用前缀
     * @param int    $ttl 验证码有效时间 默认600秒
     * @return bool|string $id, 当$id为空时，会生成一个唯一ID并返回
     * @throws \Base\Exception $type的类型不存在
     */
    public function create($type, $length, $id = null, $prefix = '', $ttl = 600) {
        $code = Util::create($type, $length);
        $this->code = $code;

        if (!$id) {
            $id = Util::createID();
        }
        $ret = Store::set($prefix . $id, $code, $ttl);
        return $ret ? $id : false;
    }

    /**
     * 展示验证码
     *
     * @param string $mode 展示模式 当前支持'sms'(短信), 'image'(图片)
     * @param array  $args 参数数组
     *          短信: array(
     * 'phone'    手机号
     *                  'template' 正文模板 用%captcha%代替验证码的位置
     *                  'service'  使用的短信服务 不配置则默认使用openapi的短信服务
     *              )
     *          图片: array(
     * 'width' 图片宽度 int 默认80
     *                  'height'图片高度 int 默认30
     *                  'size'  验证码文字点字体大小 int 不设置则使用默认值
     *              )
     *
     * @return mixed
     * @throw Exception
     */
    public function show($mode, array $args) {
        $handler = __NAMESPACE__ . "\\Handler\\" . ucfirst($mode);
        $obj_handler = new $handler;
        $args['code'] = $this->code;

        return call_user_func(array($obj_handler, __FUNCTION__), $args);
    }

    /**
     * 校验验证码
     *
     * @param string $val_code 用户输入的验证码
     * @param string $id 验证码ID
     * @param string $prefix 验证码前缀 如果生成验证码时使用了前缀，校验时需使用相同的前缀
     * @param bool   $clear 验证码的失效规则  默认true
     *                         true 一次校验后失效  false 只有验证成功后或超出有效时间才会失效
     *                         int  在校验int次后失效 当校验失败并且验证码失效时返回0
     * @return bool|int        只有在$clear为int值有情况返回0值  其他情况均成功返回true 失败返回false
     */
    public static function validate($val_code, $id, $prefix = '', $clear = true) {
        if (empty($val_code)) {
            return false;
        }

        $code = Store::get($prefix . $id);
        if (strtolower($code) === strtolower($val_code)) {
            $ret = true;
        } else {
            $ret = false;
        }

        if (is_numeric($clear)) {
            $rule_name = 'code_val_limit';

            $freq = new \Security\Freq();
            $freq->add($rule_name, $prefix . $id, $clear, 600);

            if (!$freq->check($rule_name, $prefix . $id, $clear)) {
                Store::clear($prefix . $id);
                $freq->clear($rule_name, $prefix . $id);
                if (!$ret) {
                    $ret = 0;
                }
            }

            if ($ret) {
                $freq->clear($rule_name, $prefix . $id);
            }
        }

        if ($clear === true || $ret) {
            Store::clear($prefix . $id);
        }

        return $ret;
    }

}