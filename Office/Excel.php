<?php

namespace Office;

use Base\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Exception as PhpOfficeException;
use Util\Guid;

class Excel
{
    /**
     * @param $file_path
     *
     * @return array
     * @throws Exception
     */
    public static function read($file_path)
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
     *
     * @param $data
     * @param $basePath
     * @return string
     * @throws Exception
     * @throws PhpOfficeException
     */
    public static function write($data, $basePath): string
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator('kbone.net')->setTitle('kbone.net');
        $spreadsheet->setActiveSheetIndex(0);

        foreach ($data as $key => $value) {
            $value = array_values($value);
            foreach ($value as $index => $item) {
                $position = self::colKey($index) . ($key + 1);
                $spreadsheet->getActiveSheet()->setCellValue($position, $item);
            }
        }

        $spreadsheet->getActiveSheet()->setTitle('sheet1');

        try {
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $filePath = Guid::getUid() . ".xlsx";
            $savePath = str_replace('//', '/', $basePath . DIRECTORY_SEPARATOR . $filePath);
            $writer->save($savePath);
        } catch (\Exception $exception) {
            throw new \Base\Exception($exception->getMessage());
        }
        return $filePath;
    }

    private static function colKey($index): string
    {
        $prefix = $index > 25 ? chr(64 + (int)($index / 26)) : "";
        return $prefix . chr(65 + $index % 26);
    }
}