<?php
namespace Modules\Admin\Model\Dao\Db;

class Category extends Db {
    const STATUS_CANCEL = 1;
    const STATUS_OPEN = 2;
    const STATUS_MAP = array(
        self::STATUS_CANCEL => "关闭",
        self::STATUS_OPEN => "开启",
    );

    protected $table = 'admin_category';
}