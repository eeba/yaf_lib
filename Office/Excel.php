<?php

namespace Office;

use Base\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Exception as PhpOfficeException;
use PhpOffice\PhpSpreadsheet\Writer\Exception as PhpOfficeWriterException;

class Excel
{
    /**
     * @param $file_path
     *
     * @return array
     * @throws Exception
     */
    public static function read($file_path): array
    {
        set_time_limit(0);
        $curr_mem_limit = ini_get("memory_limit");
        $data = [];
        try {
            $spreadsheet = IOFactory::load($file_path);
            $data = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        } catch (\Exception $e) {
            throw new Exception("加载文件发生错误：" . pathinfo($file_path, PATHINFO_BASENAME) . ": " . $e->getMessage());
        } finally {
            ini_set("memory_limit", $curr_mem_limit);
        }

        return $data;
    }

    /**
     * @param $data
     * @param $path
     * @throws PhpOfficeException
     * @throws PhpOfficeWriterException
     * @throws Exception
     */
    public static function write($data, $path)
    {
        $client = (new \Base\Dao\Redis())->getInstance();
        $pool = new \Cache\Adapter\Redis\RedisCachePool($client);
        $simpleCache = new \Cache\Bridge\SimpleCache\SimpleCacheBridge($pool);
        \PhpOffice\PhpSpreadsheet\Settings::setCache($simpleCache);

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator('kbone.net')->setTitle('kbone.net');
        $spreadsheet->setActiveSheetIndex(0);

        foreach ($data as $key => $value) {
            $position_y = $key + 1;
            $value = array_values($value);
            foreach ($value as $index => $item) {
                $position_x = chr(65 + $index);
                $position = $position_x . $position_y;
                $spreadsheet->getActiveSheet()->setCellValue($position, $item);
            }
        }

        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->getActiveSheet()->setTitle('sheet1');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($path);
    }
}