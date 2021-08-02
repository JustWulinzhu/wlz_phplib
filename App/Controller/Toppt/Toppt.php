<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/8/20 下午3:00
 * Email: 18515831680@163.com
 *
 * ajax请求，前端js无法直接指定浏览器输出下载文件，解决方案为ajax请求成功后跳转到下载地址进行下载
 *
 */

namespace App\Controller\Toppt;

use App\Data\Toppt as DataToppt;
use S\Log;

header('Access-Control-Allow-Origin:*');

class Toppt extends \App\Controller\Base {

    protected $verify = false;

    const OFFICE_FILE_PATH = '/data1/www/file/tmp/';

    /**
     * @param $args
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \S\Exceptions
     * @throws \Exception
     */
    public function index($args) {
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

            $server_address = \App\Data\Map::getCityByIp(isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '');
            $client_address = \App\Data\Map::getCityByIp(isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
            Log::getInstance()->debug(['create PPT', $file_name, DataToppt::$file_content_type_map[$file_content_type], $new_file_path, $server_address, $client_address]);

            $data = urlencode($new_file_path);
        } catch (\S\Exceptions $e) {
            throw new \S\Exceptions($e->getMessage(), $e->getCode());
        }

        return $data;
    }

}