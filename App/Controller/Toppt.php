<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/3/25 下午2:19
 * Email: 18515831680@163.com
 *
 * ajax请求，前端js无法直接指定浏览器输出下载文件，解决方案为ajax请求成功后跳转到下载地址进行下载
 *
 */

namespace App\Controller;

header('Access-Control-Allow-Origin:*');

use S\Log;

class Toppt extends \App\Controller\Base
{

    protected $verify = false;

    const OFFICE_FILE_PATH = '/www/tmp/file/';

    /**
     * @param $arr
     */
    public function index($arr)
    {
        $this->smarty->assign("APP_HOST", APP_HOST);
        $this->smarty->display("Toppt/Index.html");
    }

    /**
     * @throws \S\Exceptions
     * @throws \Exception
     */
    public function doUpload()
    {
        $this->response_format = self::RESPONSE_FORMAT_JSON;

        try {
            if ('default' == ($file_content_type = \S\Param::post('type'))) {
                throw new \S\Exceptions('请选择文件内容类型');
            }
            if (empty($_FILES['file']['name']) || 0 === $_FILES['file']['size']) {
                throw new \S\Exceptions('请选择要上传的文件');
            }
            $file_name = $_FILES['file']['name'];
            $new_file_name = pathinfo($file_name)['filename'] . '.ppt';

            $file_path = self::OFFICE_FILE_PATH . $file_name;
            $move_file = move_uploaded_file($_FILES['file']['tmp_name'], $file_path);
            if ($move_file) {
                chmod($file_path, 0777);

                $new_file_path = (new \App\Data\Toppt())->transToPPT($file_content_type, $file_path, $new_file_name);
            }
            Log::getInstance()->debug(['create PPT', $file_name, $new_file_path]);
            $this->response['data'] = urlencode($new_file_path);
        } catch (\S\Exceptions $e) {
            $this->response['code'] = $e->getCode();
            $this->response['msg'] = $e->getMessage();
        }
    }

    /**
     * 浏览器文件下载
     * @throws \Exception
     */
    public function download() {
        $file_path = urldecode(\S\Param::get('path'));
        Log::getInstance()->debug([__METHOD__, $file_path]);
        \S\Oss\Files::setHeader(pathinfo($file_path)['basename']);
        readfile($file_path);
    }

}