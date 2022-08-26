<?php
/**
* Yaf自动补全类(基于最新的3.3.3版本)
* @author shixinke(http://www.shixinke.com)
* @modified 2021/12/01
*/

/**
*
*/
interface Yaf_View_Interface
{
    /**
     * 
     *
     * @example 
     * @param  mixed $name 
     * @param  mixed $value 
     * @return 
     */
    public function assign($name, $value);

    /**
     * 
     *
     * @example 
     * @param  mixed $tpl 
     * @param  mixed $tpl_vars 
     * @return 
     */
    public function display($tpl, $tpl_vars);

    /**
     * 
     *
     * @example 
     * @param  mixed $tpl 
     * @param  mixed $tpl_vars 
     * @return 
     */
    public function render($tpl, $tpl_vars);

    /**
     * 
     *
     * @example 
     * @param  mixed $template_dir 
     * @return 
     */
    public function setScriptPath($template_dir);

    /**
     * 
     *
     * @example 
     * @param  mixed $request 
     * @return 
     */
    public function getScriptPath($request);

}

