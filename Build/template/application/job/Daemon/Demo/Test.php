<?php
namespace Job\Daemon\Demo;

class Test extends \Thread\Worker{
    protected $whileSleep = 10;
    public function process(){
        echo date('Y-m-d H:i:s');
    }
}