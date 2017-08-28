<?php
namespace Office;

class Word {
    public $checkbox = array('□', '■');
    public $radio = array('◇', '◆');

    /**
     * 渲染固定模板docx
     * ### 注意：该插件支持word2007文档，mac自带的文档编辑器生成的docx文件变量标识不能被替换。###
     *
     * @param string $template 模板路径及模板名（绝对路径），如：/path/example.docx
     * @param array $data 渲染模板数据
     *
     * ===================
     * @example 
     * 渲染前模板文档中的内容：
     * 诠释自己是${var1}，${var2}！
     * 喜欢什么水果？苹果${var3.1} 香蕉${var3.2} 西瓜${var3.3}
     * 是猿猴？是${var4.1} 否${var4.2}
     *
     * $data = [
     *     'var1' => '猿猴', 
     *     'var2' => '你懂的', 
     *     'var3' => [ // 多选
     *          'type' => 'checkbox',
     *          'value' => [1,2],
     *          'option' => [1,2,3],
     *     ],
     *     'var4' => [ // 单选
     *          'type' => 'radio',
     *          'value' => [1],
     *          'option' => [1,2],
     *     ],
     * ];
     * 
     * 渲染后新生成文档中的内容：
     * 诠释自己是猿猴，你懂的！
     * 喜欢什么水果？苹果■ 香蕉■ 西瓜□
     * 是猿猴？是◆ 否◇ 
     * ==================
     *
     * @return string
     * @throws \Base\Exception
     */
    public function renderTpl($template, array $data){
        if(!is_file($template)) {
            throw new \Base\Exception(get_class($this).' need be template. not fond template: '.$template);
        }

        $word = new \PhpOffice\PhpWord\TemplateProcessor($template);
        foreach($data as $template_name => $template_value) {
            if(is_array($template_value)) {
                $type = $this->$template_value['type'];
                foreach($template_value['option'] as $value) {
                    $word->setValue("{$template_name}.{$value}", $type[(int)in_array($value, $template_value['value'])]);
                }
            } else {
                $word->setValue($template_name, $template_value);
            }
        }

        return $word->save();
    }
}
