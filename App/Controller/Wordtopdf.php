<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/3/20 下午2:50
 * Email: 18515831680@163.com
 */

namespace App\Controller;

use S\Log;

class Wordtopdf extends \App\Controller\Base
{

    const OFFICE_FILE_PATH = '/www/tmp/file/';

    /**
     * @param $arr
     * @throws \SmartyException
     */
    public function index($arr)
    {
        $this->smarty->display("Wordtopdf/Index.html");
    }

    /**
     * $command
     * @throws \S\Exceptions
     * @throws \Exception
     */
    public function doUpload() {
        $file_name = $_FILES['file']['name'];
        $new_file_name = pathinfo($file_name)['filename'] . '.pdf';

        $file_path = self::OFFICE_FILE_PATH . $file_name;
        $new_file_path = self::OFFICE_FILE_PATH . $new_file_name;
        $move_file = move_uploaded_file($_FILES['file']['tmp_name'], $file_path);
        if ($move_file) {
            chmod($file_path, 0777);
            $command = "java -jar /usr/local/jodconverter/jodconverter-2.2.2/lib/jodconverter-cli-2.2.2.jar {$file_path} {$new_file_path}";
            Log::getInstance()->debug(['command', $command]);

            $ret = exec($command);
            if (! $ret) {
                throw new \S\Exceptions("文件转换失败");
            }
        }
    }

}