<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 19/4/18 下午5:10
 * excel类
 * (new Excel())->read($file, $sheet);
 */

namespace S;

require_once dirname(__DIR__) . '/Ext/excel/PHPExcel.php';
require_once dirname(__DIR__) . '/Ext/excel/PHPExcel/IOFactory.php';
require_once dirname(__DIR__) . '/Ext/excel/PHPExcel/Reader/Excel5.php';

class Excel {

    const DRIVE_IOFACTORY = 'IOFactory';
    const DRIVE_PHP_EXCEL = 'PHPExcel';

    private $drive;

    /**
     * Excel constructor.
     * @param string $drive
     * @throws \Exception
     */
    public function __construct($drive = self::DRIVE_IOFACTORY) {
        $this->drive = $drive;
        Log::getInstance()->debug(array('excel_drive', $this->drive));
        if (! in_array($drive, array(self::DRIVE_IOFACTORY, self::DRIVE_PHP_EXCEL))) {
            throw new \Exception('不存在的excel驱动');
        }
    }

    /**
     * 读取excel内容
     * @param $file
     * @param int $sheet
     * @return array|bool
     * @throws \Exception
     */
    public function read($file, $sheet = 0) {
        if ($this->drive == self::DRIVE_IOFACTORY) return $this->readIoFactory($file, $sheet);
        if ($this->drive == self::DRIVE_PHP_EXCEL) return $this->readPHPExcel($file, $sheet);
        return false;
    }

    /**
     * @param $file
     * @param $sheet
     * @return array
     * @throws \Exception
     */
    public function readPHPExcel($file, $sheet) {
        $excel = new PHPExcel_Reader_Excel2007();
        if(! $excel->canRead($file)) {
            $excel = new PHPExcel_Reader_Excel5();
            if(! $excel->canRead($file)) {
                throw new \Exception('文件不可读');
            }
        }
        $load = $excel->load($file);
        $sheet = $load->getSheet($sheet);
        $highest_row = $sheet->getHighestRow();
        $highest_column = $sheet->getHighestColumn();

        $data = [];
        for($row = 1; $row <= $highest_row; $row++) {
            $row_data = $sheet->rangeToArray('A' . $row . ':' . $highest_column . $row, null, true, false);
            $data[] = $row_data[0];
        }
        Log::getInstance()->debug(array('excel_data', json_encode($data)));
        return $data;
    }

    /**
     * @param $file
     * @param $sheet
     * @return array
     * @throws \Exception
     */
    public function readIoFactory($file, $sheet) {
        try {
            $file_type = PHPExcel_IOFactory::identify($file);
            $obj_reader = PHPExcel_IOFactory::createReader($file_type);
            $obj_excel = $obj_reader->load($file);
        } catch (\Exception $e) {
            throw new \Exception('加载文件出错');
        }

        $sheet = $obj_excel->getSheet($sheet);
        $highest_row = $sheet->getHighestRow();
        $highest_column = $sheet->getHighestColumn();

        $data = array();
        for ($row = 1; $row <= $highest_row; $row++) {
            $data[] = $sheet->rangeToArray('A' . $row . ':' . $highest_column . $row, null, true, false);
        }
        Log::getInstance()->debug(array('excel_data', json_encode($data)));
        return $data;
    }

}