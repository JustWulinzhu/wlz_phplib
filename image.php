<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 19/4/3 下午6:03
 * 图片流处理
 */

require_once "fun.php";

class Image {

    /**
     * 图片流处理,展示图片
     * @param $image_base64_info 图片流,经过base64encode之后的
     * @return string
     * @throws Exception
     */
    public function getImage($image_base64_info) {
        $data = base64_decode($image_base64_info);

        $type = $this->getFileType($data);

        $im = imagecreatefromstring($data);
        if ($im) {
            switch ($type) {
                case 'jpeg' :
                    header("Content-Type: image/jpeg");
                    imagejpeg($im);
                    break;
                case 'png' :
                    header("Content-Type: image/png");
                    imagepng($im);
                    break;
                case 'gif' :
                    header("Content-Type: image/gif");
                    imagegif($im);
                    break;
                default :
                    header("Content-Type: image/png");
                    imagepng($im);
            }
            imagedestroy($im);
        } else {
            throw new Exception('识别失败,不支持的图像类型或图片已损坏无法加载');
        }
        return $data;
    }

    /**
     * 通过文件流获取文件类型
     * @param $file 二进制文件流形式
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

$binary = file_get_contents('/Users/wulinzhu/Documents/a.png');
$base64_binary = base64_encode($binary);
(new Image())->getImage($base64_binary);