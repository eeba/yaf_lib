<?php
/**
* Yaf自动补全类(基于最新的3.3.3版本)
* @author shixinke(http://www.shixinke.com)
* @modified 2021/12/01
*/

/**
*
*/
abstract class Yaf_Plugin_Abstract
{
    /**
     * 
     *
     * @example 
     * @param Yaf_Request_Abstract $request 
     * @param Yaf_Response_Abstract $response 
     * @return 
     */
    public function routerStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response)
    {
    
    }

    /**
     * 
     *
     * @example 
     * @param Yaf_Request_Abstract $request 
     * @param Yaf_Response_Abstract $response 
     * @return 
     */
    public function routerShutdown(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response)
    {
    
    }

    /**
     * 
     *
     * @example 
     * @param Yaf_Request_Abstract $request 
     * @param Yaf_Response_Abstract $response 
     * @return 
     */
    public function dispatchLoopStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response)
    {
    
    }

    /**
     * 
     *
     * @example 
     * @param Yaf_Request_Abstract $request 
     * @param Yaf_Response_Abstract $response 
     * @return 
     */
    public function dispatchLoopShutdown(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response)
    {
    
    }

    /**
     * 
     *
     * @example 
     * @param Yaf_Request_Abstract $request 
     * @param Yaf_Response_Abstract $response 
     * @return 
     */
    public function preDispatch(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response)
    {
    
    }

    /**
     * 
     *
     * @example 
     * @param Yaf_Request_Abstract $request 
     * @param Yaf_Response_Abstract $response 
     * @return 
     */
    public function postDispatch(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response)
    {
    
    }

    /**
     * 
     *
     * @example 
     * @param Yaf_Request_Abstract $request 
     * @param Yaf_Response_Abstract $response 
     * @return 
     */
    public function preResponse(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response)
    {
    
    }

}

