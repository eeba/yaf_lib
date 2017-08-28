<?php

namespace Office;

class Excel {
    private $_creator = ''; // 建造者
    private $_lastModified = ''; // 最后修改时间
    private $_title = ''; // 标题
    private $_subject = ''; // 主题
    private $_description = ''; // 描述
    private $_keywords = 'office 2007 openxml'; // 关键字
    private $_category = ''; // 类别

    /**
     * 有需要设置其他信息的朋友
     *
     * =================
     * @example
     *
     * $object = new Excel;
     * $object->_subject = '主题';
     * $object->_description = '描述';
     * ...
     * =================
     */
    public function __set($name, $value) {
        $this->$name = $value;
    }

    /**
     * 将数据生成到表格中并导出excel
     *
     * @param array $title
     * @param array $data
     * @return string
     * @throws \Exception
     *
     * ===================
     * @example
     *
     * $title = array(
     *    'name' => '姓名',
     *    'phone' => '电话',
     * );
     *
     * @data = array(
     *     array(
     *         'name' => '东东',
     *         'phone' => '13301350882',
     *     ),
     *     array(
     *         'name' => '西西',
     *         'phone' => '18301350882',
     *     ),
     * );
     * ==================
     */
    public function export(array $title, array $data, $flag = false) {
        $filename = "/tmp/".uniqid().".xls";

        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties()->setCreator($this->_creator)
            ->setLastModifiedBy($this->_lastModified)
            ->setTitle($this->_title)
            ->setSubject($this->_subject)
            ->setDescription($this->_description)
            ->setKeywords($this->_keywords)
            ->setCategory($this->_category);
        // 创建sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // 设置标题
        $i = $cols = 65;
        $colsName = '';
        foreach($title as $v) {
            $abc = chr($i);
            $chrk = $colsName.$abc.'1';
            $objPHPExcel->getActiveSheet()->setCellValue($chrk, $v);
            $objPHPExcel->getActiveSheet()->getColumnDimension($colsName.$abc)->setWidth(17);
            $i++;
            if($i > 90) {
                $i = 65;
                $colsName = chr($cols);
                $cols++;
            }
        }

        // 设置固定标题栏
        $objPHPExcel->getActiveSheet()->freezePane('A2');
        $objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 1);
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(25);

        // 设置内容
        $j = 2;
        foreach($data as $vv) {
            $i = $cols = 65;
            $colsName = '';
            foreach($title as $key => $val) {
                $stylek = $colsName.chr($i).$j;
                $objPHPExcel->getActiveSheet()->setCellValueExplicit($stylek, $vv[$key]);
                $i++;
                if($i > 90) {
                    $i = 65;
                    $colsName = chr($cols);
                    $cols++;
                }
            }
            $j++;
        }

        // 设置全局文档样式
        $sharedStyle = new \PHPExcel_Style();
        $sharedStyle->applyFromArray(array(
            'borders'=> array(
                'bottom'=> array('style'=>\PHPExcel_Style_Border::BORDER_THIN, 'color'=>array('argb'=>'FFBBBBBB')),
                'right'=>array('style'=>\PHPExcel_Style_Border::BORDER_THIN, 'color'=>array('argb'=>'FFBBBBBB'))),
            'alignment'=>array('horizontal'=>\PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'=>\PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap'=>true),
            'font'=>array('size'=>10, 'name'=>'宋体'),
        ));
        $objPHPExcel->getActiveSheet()->duplicateStyle($sharedStyle, "A1:{$stylek}");

        // 设置内容区第一列左对齐
        $objPHPExcel->getActiveSheet()->getStyle('A2:A'.($j-1))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        // 设置标题样式
        $objPHPExcel->getActiveSheet()->getStyle('A1:'.$chrk)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A1:'.$chrk)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFF5F5F5');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($filename);
        if($flag){
            return $filename;
        }
        $content = file_get_contents($filename);
        unlink($filename);
        return $content;
    }

    /**
     * 将数据生成到表格中并导出excel,支持一个excel中多个sheet表单
     *
     * @param array $title              表单标题
     * @param array $data               内容
     * @param array $sheet_name         表单文件名
     * @param array $return_content     是返回内容还是临时文件路径
     * @return string
     * @throws \Exception
     *
     * ===================
     * @example
     *
     * $title = array(
     * 0=>array(
     *    'name' => '姓名',
     *    'phone' => '电话',
     * ),
     * 1=>
     * );
     *
     * @data = array(
     * 0=>array(
     *     array(
     *         'name' => '东东',
     *         'phone' => '13301350882',
     *     ),
     *     array(
     *         'name' => '西西',
     *         'phone' => '18301350882',
     *     ),
     * ),
     * 1=>
     * );
     * $sheet_name = array(
     * 0=>"sheet1",
     * 1=>"sheet2",
     * );
     *
     *$return_content=false，返回临时文件路径，使用后应该要删除无用的临时文件
     * ==================
     */
    public function exportMulti(array $title, array $data, array $sheet_name, $return_content = true) {
        if (empty($title) || !is_array($title) || count($title) != count($data)) {
            return false;
        }
        $filename = "/tmp/" . uniqid() . ".xsl";

        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties()->setCreator($this->_creator)->setLastModifiedBy($this->_lastModified)
            ->setTitle($this->_title)->setSubject($this->_subject)->setDescription($this->_description)
            ->setKeywords($this->_keywords)->setCategory($this->_category);

        // 创建sheet表单
        $index = 0;
        foreach ($title as $k => $v) {
            $this->creatActiveSheet($objPHPExcel, $index, $title[$k], $data[$k], $sheet_name[$k]);
            $index++;
        }

        // 保存文件
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($filename);
        if ($return_content) {
            $content = file_get_contents($filename);
            // 删除临时文件
            unlink($filename);
            // 返回文件内容
            return $content;
        }

        //返回文件路径
        return $filename;
    }

    /**
     * @param $objPHPExcel
     * @param $index
     * @return string
     * @throws \Exception
     */
    public function creatActiveSheet($objPHPExcel, $index, array $title, array $data, $sheet_name) {
        if ($index != 0) {
            $msgWorkSheet = new \PHPExcel_Worksheet($objPHPExcel); //创建一个工作表
            $objPHPExcel->addSheet($msgWorkSheet); //插入工作表
        }
        // 创建sheet
        $objPHPExcel->setActiveSheetIndex($index);
        // 设置sheet表单名
        if (!empty($sheet_name)) {
            $objPHPExcel->getActiveSheet()->setTitle($sheet_name);
        }
        // 设置标题
        $i = $cols = 65;
        $colsName = '';
        foreach ($title as $v) {
            $abc = chr($i);
            $chrk = $colsName . $abc . '1';
            $objPHPExcel->getActiveSheet()->setCellValue($chrk, $v);
            $objPHPExcel->getActiveSheet()->getColumnDimension($colsName . $abc)->setWidth(17);
            $i++;
            if ($i > 90) {
                $i = 65;
                $colsName = chr($cols);
                $cols++;
            }
        }

        // 设置固定标题栏
        $objPHPExcel->getActiveSheet()->freezePane('A2');
        $objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 1);
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(25);

        // 设置内容
        $j = 2;
        foreach ($data as $vv) {
            $i = $cols = 65;
            $colsName = '';
            foreach ($title as $key => $val) {
                $stylek = $colsName . chr($i) . $j;
                $objPHPExcel->getActiveSheet()->setCellValueExplicit($stylek, $vv[$key]);
                $i++;
                if ($i > 90) {
                    $i = 65;
                    $colsName = chr($cols);
                    $cols++;
                }
            }
            $j++;
        }

        // 设置全局文档样式
        $sharedStyle = new \PHPExcel_Style();
        $sharedStyle->applyFromArray(array('borders' => array('bottom' => array('style' => \PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFBBBBBB')), 'right' => array('style' => \PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFBBBBBB'))), 'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap' => true), 'font' => array('size' => 10, 'name' => '宋体'),));
        $objPHPExcel->getActiveSheet()->duplicateStyle($sharedStyle, "A1:{$stylek}");

        // 设置内容区第一列左对齐
        $objPHPExcel->getActiveSheet()->getStyle('A2:A' . ($j - 1))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        // 设置标题样式
        $objPHPExcel->getActiveSheet()->getStyle('A1:' . $chrk)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A1:' . $chrk)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFF5F5F5');

        return $objPHPExcel;
    }

    /**
     * 读取Excel文件方法
     * @param $file_name        | 文件的绝对路径
     * @return array
     *
     * array(
     *    [1] => array(
     *              ['A'] => 'test'
     *              ['B'] => 'test'
     *          ),
     *    [2] => array(
     *              ['A'] => 'test'
     *              ['B'] => 'test'
     *          ),
     * );
     *
     * [注] 行标从 1 开始
     */
    public function read($file_name){
        new \PHPExcel();
        $objPHPExcel = \PHPExcel_IOFactory::load($file_name);
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
        return $sheetData;
    }
}
