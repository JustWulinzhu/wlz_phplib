<?php

namespace App\Controller\File;

class Download extends \App\Controller\Base {

    protected $verify = false;

    /**
     * @param $args
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

        $files = new \S\Oss\Files();
        $files->download($params['path']);
        $files->output($params['path']);
    }

}