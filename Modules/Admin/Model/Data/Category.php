<?php
namespace Modules\Admin\Model\Data;

use Base\Exception;
use Modules\Admin\Model\Dao\Db\Category as DaoCategory;

class Category {

    public function getInfoById($id) {
        $db = new DaoCategory();
        return $db->findById($id);
    }

    public function getList($app_id) {
        $db = new DaoCategory();
        $result = $db->getList(['app_id' => $app_id], '*', ['path' => 'asc']);
        foreach ($result as &$item) {
            $item['status_name'] = DaoCategory::STATUS_MAP[$item['status']];
        }
        return $result;
    }


    public function add($app_id, $name, $status, $parent_id = 0) {
        $db = new DaoCategory();
        $data = array(
            'app_id' => $app_id,
            'status' => $status,
            'name' => $name,
            'parent_id' => $parent_id,
        );
        $id = $db->add($data);

        $parent_info = $parent_id ? $this->getInfoById($parent_id) : ['path'=>'0'];
        $path = $parent_info['path'] . ',' . $id;
        if($parent_id){
            $db->update(['exist_child' => 1], ['id' => $parent_id]);
        }

        return $db->update(['path' => $path], ['id' => $id]);
    }

    public function update($id, $name, $status) {
        $db = new DaoCategory();
        return $db->update(['name' => $name, 'status'=>$status], ['id' => $id]);
    }

    public function del($id) {
        $db = new DaoCategory();
        $list = $db->getList(['parent_id' => $id]);
        if ($list) {
            throw new Exception('有子分类，不能删除');
        }

        return $db->delete(['id' => $id]);
    }


    public function tree($list){
        foreach ($list as $key => $item){
            $tree[$item['parent_id']][] = $item;
            $path_depth = count(explode(',', $item['path'])) - 2;
            $list[$key]['prefix'] = "<span style='margin-left:" . $path_depth*30 . "px'></span>";
        }
        return $list;
    }
}