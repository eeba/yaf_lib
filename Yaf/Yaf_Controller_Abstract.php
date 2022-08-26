<?php
/**
* Yaf自动补全类(基于最新的3.3.3版本)
* @author shixinke(http://www.shixinke.com)
* @modified 2021/12/01
*/

/**
*
*/
abstract class Yaf_Controller_Abstract
{
    /**
     * 
     *
     * @example 
     * @param  mixed $request 
     * @param  mixed $response 
     * @param  mixed $view 
     * @param array $args 
     * @return 
     */
    public function __construct($request, $response, $view, Array $args)
    {
    
    }

    /**
     * 
     *
     * @example 
     * @param  mixed $tpl 
     * @param array $parameters 
     * @return 
     */
    protected function render($tpl, Array $parameters)
    {
    
    }

    /**
     * 
     *
     * @example 
     * @param  mixed $tpl 
     * @param array $parameters 
     * @return 
     */
    protected function display($tpl, Array $parameters)
    {
    
    }

    /**
     * 
     *
     * @example 
     * @return 
     */
    public function getRequest()
    {
    
    }

    /**
     * 
     *
     * @example 
     * @return 
     */
    public function getResponse()
    {
    
    }

    /**
     * 
     *
     * @example 
     * @return 
     */
    public function getView()
    {
    
    }

    /**
     * 
     *
     * @example 
     * @return 
     */
    public function getName()
    {
    
    }

    /**
     * 
     *
     * @example 
     * @return 
     */
    public function getModuleName()
    {
    
    }

    /**
     * 
     *
     * @example 
     * @param array $options 
     * @return 
     */
    public function initView(Array $options)
    {
    
    }

    /**
     * 
     *
     * @example 
     * @param  mixed $view_directory 
     * @return 
     */
    public function setViewpath($view_directory)
    {
    
    }

    /**
     * 
     *
     * @example 
     * @return 
     */
    public function getViewpath()
    {
    
    }

    /**
     * 
     *
     * @example 
     * @param  mixed $module 
     * @param  mixed $controller 
     * @param  mixed $action 
     * @param array $parameters 
     * @return 
     */
    public function forward($module, $controller, $action, Array $parameters)
    {
    
    }

    /**
     * 
     *
     * @example 
     * @param  mixed $url 
     * @return 
     */
    public function redirect($url)
    {
    
    }

    /**
     * 
     *
     * @example 
     * @return 
     */
    public function getInvokeArgs()
    {
    
    }

    /**
     * 
     *
     * @example 
     * @param  mixed $name 
     * @return 
     */
    public function getInvokeArg($name)
    {
    
    }

}

