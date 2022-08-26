<?php
/**
* Yaf自动补全类(基于最新的3.3.3版本)
* @author shixinke(http://www.shixinke.com)
* @modified 2021/12/01
*/

/**
*
*/
abstract class Yaf_Request_Abstract
{
    /**     
    *
    */
    const SCHEME_HTTP    =    'http';

    /**     
    *
    */
    const SCHEME_HTTPS    =    'https';

    /**
     * 
     *
     * @example 
     * @return 
     */
    public function isGet()
    {
    
    }

    /**
     * 
     *
     * @example 
     * @return 
     */
    public function isPost()
    {
    
    }

    /**
     * 
     *
     * @example 
     * @return 
     */
    public function isDelete()
    {
    
    }

    /**
     * 
     *
     * @example 
     * @return 
     */
    public function isPatch()
    {
    
    }

    /**
     * 
     *
     * @example 
     * @return 
     */
    public function isPut()
    {
    
    }

    /**
     * 
     *
     * @example 
     * @return 
     */
    public function isHead()
    {
    
    }

    /**
     * 
     *
     * @example 
     * @return 
     */
    public function isOptions()
    {
    
    }

    /**
     * 
     *
     * @example 
     * @return 
     */
    public function isCli()
    {
    
    }

    /**
     * 
     *
     * @example 
     * @return 
     */
    public function isXmlHttpRequest()
    {
    
    }

    /**
     * 
     *
     * @example 
     * @param  mixed $name 
     * @param  mixed $default 
     * @return 
     */
    public function getQuery($name, $default)
    {
    
    }

    /**
     * 
     *
     * @example 
     * @param  mixed $name 
     * @param  mixed $default 
     * @return 
     */
    public function getRequest($name, $default)
    {
    
    }

    /**
     * 
     *
     * @example 
     * @param  mixed $name 
     * @param  mixed $default 
     * @return 
     */
    public function getPost($name, $default)
    {
    
    }

    /**
     * 
     *
     * @example 
     * @param  mixed $name 
     * @param  mixed $default 
     * @return 
     */
    public function getCookie($name, $default)
    {
    
    }

    /**
     * 
     *
     * @example 
     * @return 
     */
    public function getRaw()
    {
    
    }

    /**
     * 
     *
     * @example 
     * @param  mixed $name 
     * @param  mixed $default 
     * @return 
     */
    public function getFiles($name, $default)
    {
    
    }

    /**
     * 
     *
     * @example 
     * @param  mixed $name 
     * @param  mixed $default 
     * @return 
     */
    public function get($name, $default)
    {
    
    }

    /**
     * 
     *
     * @example 
     * @param  mixed $name 
     * @param  mixed $default 
     * @return 
     */
    public function getServer($name, $default)
    {
    
    }

    /**
     * 
     *
     * @example 
     * @param  mixed $name 
     * @param  mixed $default 
     * @return 
     */
    public function getEnv($name, $default)
    {
    
    }

    /**
     * 
     *
     * @example 
     * @param  mixed $name 
     * @param  mixed $value 
     * @return 
     */
    public function setParam($name, $value)
    {
    
    }

    /**
     * 
     *
     * @example 
     * @param  mixed $name 
     * @param  mixed $default 
     * @return 
     */
    public function getParam($name, $default)
    {
    
    }

    /**
     * 
     *
     * @example 
     * @return 
     */
    public function getParams()
    {
    
    }

    /**
     * 
     *
     * @example 
     * @return 
     */
    public function clearParams()
    {
    
    }

    /**
     * 
     *
     * @example 
     * @return 
     */
    public function getException()
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
     * @return 
     */
    public function getControllerName()
    {
    
    }

    /**
     * 
     *
     * @example 
     * @return 
     */
    public function getActionName()
    {
    
    }

    /**
     * 
     *
     * @example 
     * @param  mixed $module 
     * @param  mixed $format_name 
     * @return 
     */
    public function setModuleName($module, $format_name)
    {
    
    }

    /**
     * 
     *
     * @example 
     * @param  mixed $controller 
     * @param  mixed $format_name 
     * @return 
     */
    public function setControllerName($controller, $format_name)
    {
    
    }

    /**
     * 
     *
     * @example 
     * @param  mixed $action 
     * @param  mixed $format_name 
     * @return 
     */
    public function setActionName($action, $format_name)
    {
    
    }

    /**
     * 
     *
     * @example 
     * @return 
     */
    public function getMethod()
    {
    
    }

    /**
     * 
     *
     * @example 
     * @return 
     */
    public function getLanguage()
    {
    
    }

    /**
     * 
     *
     * @example 
     * @param  mixed $uri 
     * @return 
     */
    public function setBaseUri($uri)
    {
    
    }

    /**
     * 
     *
     * @example 
     * @return 
     */
    public function getBaseUri()
    {
    
    }

    /**
     * 
     *
     * @example 
     * @return 
     */
    public function getRequestUri()
    {
    
    }

    /**
     * 
     *
     * @example 
     * @param  mixed $uri 
     * @return 
     */
    public function setRequestUri($uri)
    {
    
    }

    /**
     * 
     *
     * @example 
     * @return 
     */
    public final  function isDispatched()
    {
    
    }

    /**
     * 
     *
     * @example 
     * @param  mixed $dispatched 
     * @return 
     */
    public final  function setDispatched($dispatched)
    {
    
    }

    /**
     * 
     *
     * @example 
     * @return 
     */
    public final  function isRouted()
    {
    
    }

    /**
     * 
     *
     * @example 
     * @param  mixed $flag 
     * @return 
     */
    public final  function setRouted($flag)
    {
    
    }

}

