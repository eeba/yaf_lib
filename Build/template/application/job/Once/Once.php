<?php
namespace Job\Once;

/**
 * 一次性
 * @package Job\Once
 */
class Once extends \Base\Job {

    public function action($argv = []){
        //coding
        echo 'hello';
        var_dump($argv);
    }
}