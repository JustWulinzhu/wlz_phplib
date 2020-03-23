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

    public function read() {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        dd($phpWord);
    }

}