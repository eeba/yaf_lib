<?php

/**
 * Created by PhpStorm.
 * User: mfhj-dz-001-279
 * Date: 2017/4/19
 * Time: 下午4:27
 */
class HistoryTest extends \Base\TestCase {

    /**
     * @dataProvider addProvider
     */
    public function testAdd($data){
        $ret = (new \Dao\Db\Novel\History())->allReadBookId(1222);
        var_dump($ret);
    }
}
