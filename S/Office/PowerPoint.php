<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/3/23 上午10:08
 * Email: 18515831680@163.com
 *
 * PPT核心类
 * 注意：需要引入PHPPresentation Common两个库，github：https://github.com/PHPOffice
 * 官方文档：https://phppresentation.readthedocs.io/en/latest/styles.html#font
 *
 * demo: (new \S\Office\PowerPoint())->create();
 *
 */

namespace S\Office;

use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\Style\Alignment;
use PhpOffice\PhpPresentation\Style\Bullet;
use PhpOffice\PhpPresentation\Style\Color;
use \S\Tools;
use S\Log;

require_once APP_ROOT_PATH . '/Ext/PHPPresentation/src/PhpPresentation/Autoloader.php';
\PhpOffice\PhpPresentation\Autoloader::register();
require_once APP_ROOT_PATH . '/Ext/Common/src/Common/Autoloader.php';
\PhpOffice\Common\Autoloader::register();

date_default_timezone_set('PRC'); //中国时区

class PowerPoint {

    const DEFAULT_FILE_PATH = '/www/tmp/file/ppt';

    private $PPT;

    public function __construct() {
        //新建立一个PHPPowerPoint对象
        $this->PPT = new PhpPresentation();
    }

    /**
     * 生成PPT, 返回生成的文件路径
     * @param array $data 格式要求：二维数组，里层每一个数组元素为一页ppt，格式为string
     * @param $new_file_name
     * @return string
     * @throws \Exception
     */
    public function create(array $data, $new_file_name) {
        if (1 != Tools::getArrayDim($data)) {
            throw new \S\Exceptions("生成ppt的数据结构错误（需为一维数组）");
        }

        //删除首页
        $this->PPT->removeSlideByIndex(0);
        //循环创建文本
        foreach ($data as $page) {
            if (! is_string($page)) {
                throw new \S\Exceptions('生成ppt数组元素需为字符串类型！');
            }
            $this->addText($page);
        }
        //创建PPT，使用PowerPoint2007格式
        $ppt_writer = \PhpOffice\PhpPresentation\IOFactory::createWriter($this->PPT, 'PowerPoint2007');
        //文件名称
        $file_name = $new_file_name ? $new_file_name : date('Ymd-His', time()) . '-' . substr(md5(microtime(true)), 0, 8) . '.ppt';
        //文件路径
        $file_path = self::DEFAULT_FILE_PATH . DIRECTORY_SEPARATOR . $file_name;
        //保存文件
        $ppt_writer->save($file_path);
        chmod($file_path, 0777);

        return $file_path;
    }

    /**
     * 创建文本框
     * @param string $content
     * @param int $line_space
     * @param int $height
     * @param int $width
     * @param int $offsetX
     * @param int $OffsetY
     * @param string $font_style
     * @param bool $is_bold
     * @param int $size
     * @param string $color
     * @return bool
     * @throws \Exception
     */
    public function addText($content = '欢迎使用武林柱PowerPoint生成类，有问题请联系微信！', $line_space = 125, $height = 300, $width = 810, $offsetX = 70, $OffsetY = 80, $font_style = '方正兰亭黑简体', $is_bold = false, $size = 24, $color = 'FF000000') {
        $slide = $this->PPT->createSlide();

        //设置一个文本框
        $shape = $slide->createRichTextShape();
        //设置文本行间距
        $shape->getActiveParagraph()->setLineSpacing($line_space);
        //设置文本框高度, 单位像素
        $shape->setHeight($height);
        //设置文本框宽度, 单位像素
        $shape->setWidth($width);
        //设置文本框相对于左上角X位置, 单位像素
        $shape->setOffsetX($offsetX);
        //设置文本框相对于左上角Y位置, 单位像素
        $shape->setOffsetY($OffsetY);
        //设置文本布局位置为水平居中, 垂直居中
        $shape->getActiveParagraph()->getAlignment()->setHorizontal( \PhpOffice\PhpPresentation\Style\Alignment::HORIZONTAL_GENERAL );
        $shape->getActiveParagraph()->getAlignment()->setVertical( \PhpOffice\PhpPresentation\Style\Alignment::VERTICAL_BASE );

        //设置文本框文本内容
        //在中文环境下测试没中文问题，如果在 e 文环境，注意要指定支持中文的字体，否则可能出乱码了
        $text = $shape->createTextRun($content);
        //设置文本字体
        $text->getFont()->setName($font_style);
        //使用字体加粗
        $text->getFont()->setBold($is_bold ? true : false);
        //设置字体尺寸为 18, 这里注意一下文字的大小设置. 前面的文本框的大小是固定的. 如果文字超出的容器会被出容器被排到下面
        $text->getFont()->setSize($size);
        //设置文字颜色, 这里是ARGB模式 , 16进制模式, 前面2位为透明度, 后面为RGB值. 这里设置为黑色
        $text->getFont()->setColor( new \PhpOffice\PhpPresentation\Style\Color( $color ) );

        return true;
    }

    /**
     * 添加一个图片
     * @param string $name
     * @param string $intro
     * @param string $path
     * @param int $height
     * @param int $width
     * @param int $OffsetX
     * @param int $OffsetY
     * @return bool
     * @throws \Exception
     */
    public function addImg($name = 'test Logo', $intro = 'Logo intro', $path = '/www/tmp/gouerzi.png', $height = 100, $width = 300, $OffsetX = 10, $OffsetY = 10) {
        $slide = $this->PPT->createSlide();

        //添加一个图片到幻灯片
        $shape = $slide->createDrawingShape();
        //设置图片名称
        $shape->setName($name);
        //设置图片的描述信息
        $shape->setDescription($intro);
        //图片实际路径
        $shape->setPath($path);
        //设置图片高度
        $shape->setHeight($height);
        //设置图片宽度
        $shape->setWidth($width);
        //设置图片相对于左上角X位置, 单位像素
        $shape->setOffsetX($OffsetX);
        //设置图片相对于左上角Y位置, 单位像素
        $shape->setOffsetY($OffsetY);
        //设置图片显示状态
        $shape->getShadow()->setVisible(true);
        $shape->getShadow()->setDirection(45);
        $shape->getShadow()->setDistance(10);

        return true;
    }

    /**
     * 递归创建文本框
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    private function recuAddText(array $data) {
        foreach ($data as $value) {
            if (is_array($value)) {
                $value = self::recuAddText($value);
            }
            $this->addText($value);
        }

        return true;
    }

}