<?php
/**
* Yaf自动补全类(基于最新的3.3.3版本)
* @author shixinke(http://www.shixinke.com)
* @modified 2021/12/01
*/

/**
*
*/
interface Yaf_Route_Interface
{
    /**
     * 
     *
     * @example 
     * @param  mixed $request 
     * @return 
     */
    public function route($request);

    /**
     * 
     *
     * @example 
     * @param array $info 
     * @param array $query 
     * @return 
     */
    public function assemble(Array $info, Array $query);

}

