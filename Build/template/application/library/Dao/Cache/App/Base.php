<?php
namespace Dao\Cache\App;

use Dao\Cache\Cache;

class Base extends Cache{

    public function __construct(){
        $this->setConfig('BaseInfo', strtoupper(APP.'_BASE_INFO'));
    }
}