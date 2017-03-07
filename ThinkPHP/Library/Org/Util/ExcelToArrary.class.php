<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace Org\Util;
class ExcelToArrary {
    public function __construct() {
        Vendor("Excel.PHPExcel");//引入phpexcel类
        Vendor("Excel.PHPExcel.IOFactory");
    }
    public function read($filename,$encode,$file_type){
        if(strtolower ( $file_type )=='xls')//判断excel表类型为2003还是2007
        {
            Vendor("Excel.PHPExcel.Reader.Excel5");
            $objReader = \PHPExcel_IOFactory::createReader('Excel5');
        }elseif(strtolower ( $file_type )=='xlsx')
        {
            Vendor("Excel.PHPExcel.Reader.Excel2007");
            $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
        }
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($filename);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
        $excelData = array();
        for ($row = 2; $row <= $highestRow; $row++) { //从第二行开始读取
            for ($col = 0; $col < $highestColumnIndex; $col++) {
                $excelData[$row][] =(string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            }
        }
        return $excelData;
    }
}