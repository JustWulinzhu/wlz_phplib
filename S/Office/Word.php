<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/3/23 下午5:09
 * Email: 18515831680@163.com
 *
 * Word核心类
 *
 */

namespace S\Office;

require_once dirname(dirname(__DIR__)) . "/Ext/PHPWord/bootstrap.php";

class Word {

    /**
     * word读取
     * @param string $file_path
     * @param string $type
     * @return array
     */
    public function read($file_path = '/www/tmp/file/b.docx', $type = 'Word2007') {
        $phpWord = \PhpOffice\PhpWord\IOFactory::load($file_path, $type);

        $data = [];
        $sections = $phpWord->getSections();
        foreach ($sections as $section) {
            $elements = $section->getElements();
            foreach ($elements as $element) {
                $element = (array)$element;
                $element = @(array)$element["\0*\0elements"][0];
                $data[] = @$element["\0*\0text"];
                //echo $element->getElements()[0]->getText() . "\n";
            }
        }

        $data = array_map("trim", $data);
        $data = array_filter($data);
        $data = array_values($data);

        return $data;
    }

}