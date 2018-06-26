<?php

/**
 * Created by PhpStorm.
 * User: mfhj-dz-001-279
 * Date: 2017/4/19
 * Time: 下午4:27
 */
class addTest extends \Base\TestCase {

    /**
     * @dataProvider addProvider
     */
    public function testAdd($data){
        $path = '/api/article/add';
        $ret = $this->request()->request('post',$path, $data);
        print_r(json_decode($ret));
    }


    public function addProvider(){
        return array(
            array(
                array(
                    'title'=>'<h1></h1>',
                    'content'=>'<script> alert ("hello world");</script>',
                    'category'=>'test,category',
                )
            )
        );
    }
}
