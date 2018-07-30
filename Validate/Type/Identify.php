<?php
namespace Validate\Type;

use Base\Exception;
use Base\Config;

/**
 * 身份证号码验证（18位，15位数字或17位数字+X|x）
 */
class Identify extends \Validate\Abstraction {
    public function action($param) {
        if (preg_match('/(^\d{15}$)|(^\d{17}[0-9Xx]$)/', $param['value']) && self::checkIdentity($param['value'])) {
            return $param['value'];
        }

        $conf = Config::get($param['msg']);
        $error_msg = ($conf['user_msg'] ?: $conf['sys_msg']) ?: "参数格式错误";
        $error_code = $conf['code'] ?: 5001001;
        throw new Exception($error_msg, $error_code);
    }

    /**
     * @name  校验身份证合法性
     * @param $id_num
     * @return bool
     */
    private static function checkIdentity($id_num) {
        //15位无校验位
        if (strlen($id_num) == 15) {
            return true;
        }

        $id_num = strtolower($id_num);
        $set = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        $ver = array('1', '0', 'x', '9', '8', '7', '6', '5', '4', '3', '2');
        $arr = str_split($id_num);
        $sum = 0;
        for ($i = 0; $i < 17; $i++) {
            if (!is_numeric($arr[$i])) {
                return false;
            }
            $sum += $arr[$i] * $set[$i];
        }
        $mod = $sum % 11;
        if (strcasecmp($ver[$mod], $arr[17]) != 0) {
            return false;
        }
        return true;
    }
}
