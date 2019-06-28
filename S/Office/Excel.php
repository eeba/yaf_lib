<?php

namespace S\Office;
//define('PHPAES_ROOT', dirname(__FILE__));
//
//include PHPAES_ROOT . '/Classes/PHPExcel.php';

//include PHPAES_ROOT . '/PhpSpreadsheet/Spreadsheet.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

class Excel {
    /**
     * @param $file_path
     *
     * @return array
     * @throws \Base\Exception
     */
    public function read($file_path) {
        set_time_limit(0);
        $curr_mem_limit = ini_get("memory_limit");
        $data = [];
        try {
            $spreadsheet = IOFactory::load($file_path);
            $data = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        } catch (\Exception $e) {
            throw new \Base\Exception("加载文件发生错误：" . pathinfo($file_path, PATHINFO_BASENAME) . ": " . $e->getMessage());
        } finally {
            ini_set("memory_limit", $curr_mem_limit);
        }

        return $data;
    }
}