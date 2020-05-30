<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/5/30 上午1:20
 * Email: 18515831680@163.com
 *
 * php二维码核心类
 *
 * @text string
 *      二维码包含的信息，链接、文字、json字符串等等
 *
 * @logo string
 *      logo图片路径
 *
 * @outfile string
 *      输出文件路径，默认false
 *
 * @$level int
 *      容错率，表示二维码损坏多少程度还可以读取出来
 *
 * @size int
 *      二维码大小
 *
 * @margin int
 *      二维码空白区域大小
 *
 * demo:
 *
 * 生成二维码： \S\Qrcode::create('wlfeng.vip/test/demo', '/www/tmp/image/gou.png');
 * 读取二维码：\S\Qrcode::read('/www/tmp/image/qrcode/test.png');
 *
 */

namespace S;

require_once APP_ROOT_PATH . '/Ext/Qrcode/phpqrcode.php';
require_once APP_ROOT_PATH . '/Ext/Qrcode/reader/lib/QrReader.php';

class Qrcode
{

    const QECODE_PATH = '/www/tmp/image/qrcode';

    /**
     * 创建二维码并返回图片路径
     * @param $text
     * @param string $logo
     * @param bool $outfile
     * @param int $level
     * @param int $size
     * @param int $margin
     * @return bool|string
     * @throws Exceptions
     */
    public static function create($text, $logo = '', $outfile = false, $level = QR_ECLEVEL_Q, $size = 10, $margin = 3) {
        self::beforeCreate();
        $outfile = $outfile ? $outfile : self::QECODE_PATH . '/qrcode_' . date('Ymd') . '_' . uniqid() . '.png';

        if ($logo) {
            if (! file_exists($logo)) {
                throw new \S\Exceptions('logo图片不存在');
            }

            self::png($text, $outfile, $level, $size, $margin);

            $qrcode = imagecreatefromstring(file_get_contents($outfile));
            $logo = imagecreatefromstring(file_get_contents($logo));
            $qrcode_width = imagesx($qrcode);
            $logo_width = imagesx($logo);
            $logo_height = imagesy($logo);
            $logo_new_width = $qrcode_width / 4;
            $scale = $logo_width / $logo_new_width;
            $logo_new_height = $logo_height / $scale;
            $from_width = ($qrcode_width - $logo_new_width) / 2;  //组合之后logo左上角所在坐标点

            imagecopyresampled($qrcode, $logo, $from_width, $from_width, 0, 0, $logo_new_width, $logo_new_height, $logo_width, $logo_height);

            imagepng($qrcode, $outfile);
            imagedestroy($qrcode);
            imagedestroy($logo);
        } else {
            self::png($text, $outfile, $level, $size, $margin);
        }

        return $outfile;
    }

    /**
     * 二维码读取
     * @param $file_path
     * @return bool
     * @throws Exceptions
     */
    public static function read($file_path) {
        if (! file_exists($file_path)) {
            throw new \S\Exceptions('二维码图片文件不存在');
        }
        return (new \QrReader($file_path))->text();
    }

    /**
     * qrcode创建二维码
     * @param $text
     * @param $outfile
     * @param $level
     * @param $size
     * @param $margin
     */
    private static function png($text, $outfile, $level, $size, $margin) {
        return \QRcode::png($text, $outfile, $level, $size, $margin);
    }

    /**
     * @return bool
     */
    private static function beforeCreate() {
        if (! file_exists(self::QECODE_PATH)) {
            mkdir(self::QECODE_PATH, 0755, true);
        }

        return true;
    }

}