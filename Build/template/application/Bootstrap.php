<?php

class Bootstrap extends Base\Bootstrap {

    public function _initPlugin(\Yaf\Dispatcher $dispatcher) {
        $dispatcher->registerPlugin(new Plugin_Session());
        $dispatcher->registerPlugin(new Plugin_Statistic());
    }

}