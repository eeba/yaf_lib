<?php
namespace Modules\Admin\Model\Data;

use Modules\Admin\Model\Dao\Db\Menu as DaoAdminMenu;

class Menu {

    /**
     * @param $data
     *
     * @return bool|int|null
     * @throws \Exception
     */
    public function save($data) {
        $db = new DaoAdminMenu();

        $info = null;
        if ($data['id']) {
            $info = $db->find(['id' => $data['id']]);
        }
        if ($info) {
            return $db->update($data, ['id' => $data['id']]);
        } else {
            return (new DaoAdminMenu())->add($data);
        }
    }

    public function getList() {
        return (new DaoAdminMenu())->getList([], '*', ['order' => 'asc']);
    }

    public function getById($id){
        return (new DaoAdminMenu())->find(['id' => $id]);
    }

    public function deleteById($id){
        return (new DaoAdminMenu())->delete(['id' => $id]);
    }
}