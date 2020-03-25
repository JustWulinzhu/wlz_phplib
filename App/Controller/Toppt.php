<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/3/25 下午2:19
 * Email: 18515831680@163.com
 */

namespace App\Controller;

use S\Log;

class Toppt extends \App\Controller\Base
{

    const OFFICE_FILE_PATH = '/www/tmp/file/';

    /**
     * @param $arr
     */
    public function index($arr)
    {
        $this->smarty->display("Toppt/Index.html");
    }

    /**
     * @throws \S\Exceptions
     * @throws \Exception
     */
    public function doUpload()
    {
        $file_name = $_FILES['file']['name'];
        $new_file_name = pathinfo($file_name)['filename'] . '.ppt';

        $file_path = self::OFFICE_FILE_PATH . $file_name;
        $move_file = move_uploaded_file($_FILES['file']['tmp_name'], $file_path);
        if ($move_file) {
            chmod($file_path, 0777);

            $new_file_path = (new \S\Office\PowerPoint())->TransToPPT($file_path, $new_file_name);
        }
        Log::getInstance()->debug(['create PPT', $file_name, $new_file_path]);
        \S\Oss\Files::setHeader($new_file_name);

        exit(file_get_contents($new_file_path));
    }

}