<?php
namespace Modules\Admin\Model\Dao\Db;

class Db extends \Base\Dao\Db{
    use \Base\Dao\TraitDb;

    public static function db($name = \Dao\Db\Db::DB_NAME){
        return self::getInstance($name);
    }

}