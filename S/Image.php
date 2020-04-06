<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: 18515831680@163.com
 * Date: 19/4/3 下午6:03
 * 图片流处理, 展示、压缩等
 * 可采用阿里云OSS图片处理模块
 */

namespace S;

class Image {

    const TMP_PATH = '/www/tmp/image'; //默认文件保存路径

    const IMAGE_TYPE_JPEG = 'jpeg';
    const IMAGE_TYPE_PNG = 'png';
    const IMAGE_TYPE_GIF = 'gif';

    private $image = array(
        self::IMAGE_TYPE_GIF, self::IMAGE_TYPE_JPEG, self::IMAGE_TYPE_PNG,
    );

    /**
     * 图片流处理,展示图片
     * @param $image_base64_info
     * @return false|string
     * @throws \Exception
     */
    public function show($image_base64_info) {
        $data = base64_decode($image_base64_info);

        $type = $this->getFileType($data);
        Log::getInstance()->debug(array('file_type', $type));

        $im = imagecreatefromstring($data);
        if ($im) {
            header("Content-Type: image/{$type}");
            switch ($type) {
                case 'jpeg' :
                    imagejpeg($im);
                    break;
                case 'png' :
                    imagepng($im);
                    break;
                case 'gif' :
                    imagegif($im);
                    break;
                default :
                    header("Content-Type: image/jpeg");
                    imagejpeg($im);
            }
            imagedestroy($im);
        } else {
            throw new \Exception('识别失败,不支持的图像类型或图片已损坏无法加载');
        }
        return $data;
    }

    /**
     * 图片压缩下载(浏览器输出)
     * @param $image_binary
     * @param float $percent
     * @throws \Exception
     */
    public function compress($image_binary, $percent = 0.5) {
        $origin_mem_limit = ini_get("memory_limit");
        ini_set("memory_limit", "1024M");

        if ($percent <= 0 || $percent > 2) {
            throw new \Exception('压缩范围超出限制');
        }
        $type = $this->getFileType($image_binary);
        if (! in_array($type, $this->image)) {
            throw new \Exception('错误的图片类型');
        }

        list($width, $height) = getimagesizefromstring($image_binary);

        $new_width  = $percent * $width;
        $new_height = $percent * $height;

        $image = imagecreatetruecolor($new_width, $new_height);
        $image_string = imagecreatefromstring($image_binary);
        imagecopyresampled($image, $image_string, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        //指定浏览器输出下载
        $filename = date('Ymd', time()) . '_' . substr(md5(mt_rand(1, 10000)), 0, 12) . '.' . $type;
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=$filename");
        header("Pragma: no-cache");
        header("Expires: 0");

        header("Content-Type: image/{$type}");
        switch ($type) {
            case self::IMAGE_TYPE_GIF :
                imagegif($image, null);
                break;
            case self::IMAGE_TYPE_JPEG :
                imagejpeg($image, null);
                break;
            case self::IMAGE_TYPE_PNG :
                imagepng($image, null);
                break;
        }
        imagedestroy($image);
        imagedestroy($image_string);

        ini_set('memory_limit', $origin_mem_limit);
    }

    /**
     * 图片压缩,返回文件流
     * @param $image_binary
     * @param float $percent
     * @return false|string
     * @throws \Exception
     */
    public function compressBinary($image_binary, $percent = 0.5) {
        $origin_mem_limit = ini_get("memory_limit");
        ini_set("memory_limit", "256M");

        if ($percent <= 0 || $percent > 2) {
            throw new \Exception('压缩范围超出限制');
        }
        $type = $this->getFileType($image_binary);
        if (! in_array($type, $this->image)) {
            throw new \Exception('错误的图片类型');
        }

        list($width, $height) = getimagesizefromstring($image_binary);

        $new_width  = $percent * $width;
        $new_height = $percent * $height;

        $image = imagecreatetruecolor($new_width, $new_height);
        $image_string = imagecreatefromstring($image_binary);
        imagecopyresampled($image, $image_string, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        //在php临时文件目录新建临时文件,前缀image_,用于下面图片保存
        $tmp_file = tempnam(sys_get_temp_dir(), 'image_');

        switch ($type) { //保存临时图片
            case self::IMAGE_TYPE_GIF :
                imagegif($image, $tmp_file);
                break;
            case self::IMAGE_TYPE_JPEG :
                imagejpeg($image, $tmp_file);
                break;
            case self::IMAGE_TYPE_PNG :
                imagepng($image, $tmp_file);
                break;
        }
        imagedestroy($image);
        imagedestroy($image_string);
        $image_binary = file_get_contents($tmp_file);
        unlink($tmp_file);
        ini_set('memory_limit', $origin_mem_limit);

        return $image_binary;
    }

    /**
     * 修改图片尺寸
     * @param $image_binary 图片二进制文件流
     * @param $x 长
     * @param $y 宽
     * @return false|string 返回新的图片二进制文件流
     * @throws \Exception
     */
    public function modifyPicSize($image_binary, $x, $y) {
        $type = $this->getFileType($image_binary);
        if (! in_array($type, $this->image)) {
            throw new \Exception('错误的图片类型');
        }
        list($width, $height) = getimagesizefromstring($image_binary);
        $im = imagecreatefromstring($image_binary);
        $new_image = imagecreatetruecolor($x, $y);
        imagecopyresized($new_image, $im, 0, 0, 0, 0, $x, $y, $width, $height);

        $pic_name = date('YmdHis', time()) . '_' . substr(md5(microtime(true)), 0, 12) . '.' . $type; //生成新文件名称
        $path = self::TMP_PATH . '/' . $pic_name; //文件默认保存路径
        imagejpeg($new_image, $path, 100);
        imagedestroy($im);
        imagedestroy($new_image);
        return file_get_contents($path);
    }

    /**
     * 通过文件流获取文件类型
     * @param string $file 二进制文件流形式
     * @return string
     */
    public function getFileType($file) {
        $bin = substr($file, 0, 2);
        $str_info = @unpack("C2chars", $bin);;
        $type_code = intval($str_info['chars1'] . $str_info['chars2']);

        switch ($type_code) {
            case 255216 :
                $file_type = "jpeg";
                break;
            case 7173 :
                $file_type = "gif";
                break;
            case 13780 :
                $file_type = "png";
                break;
            case 6677:
                $file_type = 'bmp';
                break;
            case 7790:
                $file_type = 'exe';
                break;
            case 7784:
                $file_type = 'midi';
                break;
            case 8297:
                $file_type = 'rar';
                break;
            default :
                $file_type = "unknow";
                break;
        }

        return $file_type;
    }

}