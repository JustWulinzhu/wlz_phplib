<?php
/**
 * 文件服务器 上传接口
 */

namespace App\Controller\File;

class Upload extends \App\Controller\Base {

    protected $verify = false;

    /**
     * @param $args
     * @return string
     * @throws \S\Exceptions
     */
    public function index($args) {
        $params = \S\Param::get() ? : \S\Param::post();
        if (! isset($params['path'])) {
            throw new \S\Exceptions("请传入path参数");
        }
        if (empty($params['path'])) {
            throw new \S\Exceptions("path参数不能为空");
        }
        return (new \S\Oss\Files())->uploadLocal($params['path'], "file_engine");
    }

}