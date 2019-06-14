<?php
namespace Modules\Admin\Model\Dao\Db;

class User extends Db {
    const STATUS_OPEN = 1;
    const STATUS_CLOSE = 2;

    const STATUS_MAP = array(
        self::STATUS_OPEN => '启用',
        self::STATUS_CLOSE => '禁用',
    );

    protected $table = 'admin_user';

}