<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/4/6 下午1:56
 * Email: 18515831680@163.com
 *
 * 图片展示
 *
 */

namespace App\Controller;

use S\Log;
use S\Tools;

class Image extends \App\Controller\Base
{

    protected $verify = false;

    const MAX_IMAGE_SIZE = '2M';

    /**
     * 服务器本地图片展示
     * @param $args
     * @return bool
     * @throws \S\Exceptions
     * @throws \Exception
     */
    public function show($args) {
        $img = new \S\Image();

        if (empty($args) || (! isset($args['image'])) || empty($args['image'])) {
            throw new \S\Exceptions('缺少图片参数');
        }
        $image = $args['image'];
        $path = \S\Image::TMP_PATH . DIRECTORY_SEPARATOR . $image;
        if (! file_exists($path)) {
            Log::getInstance()->warning(['image not found', $path]);
            exit(header("Location:" . APP_HOST . DIRECTORY_SEPARATOR . "error/notfound404"));
        }

        $image_binary = file_get_contents($path);
        if (Tools::formatSize(filesize($path)) > self::MAX_IMAGE_SIZE) {
            try {
                $image_binary = $img->compressBinary(file_get_contents($path));
            } catch (\S\Exceptions $e) {
                Log::getInstance()->warning([__CLASS__, __FUNCTION__, 'compress fail', $path]);
            }
        }
        $img->show(base64_encode($image_binary));

        return true;
    }

}