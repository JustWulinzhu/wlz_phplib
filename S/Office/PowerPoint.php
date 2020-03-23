<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/3/23 上午10:08
 * Email: 18515831680@163.com
 */

namespace S\Office;

use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\Style\Alignment;
use PhpOffice\PhpPresentation\Style\Bullet;
use PhpOffice\PhpPresentation\Style\Color;

require_once dirname(dirname(__DIR__)) . '/Ext/PHPPresentation/src/PhpPresentation/Autoloader.php';
\PhpOffice\PhpPresentation\Autoloader::register();

class PowerPoint {

    private $PPT;

    public function __construct() {
        $this->PPT = new PhpPresentation();
    }

    public function create() {
        $active_slide = $this->PPT->getActiveSlide();
        $shape = $active_slide->createDrawingShape();
        $shape->setName('MmClub.net Logo');
        $shape->setDescription('MmClub.net Logo');
        $shape->setPath('/www/tmp/gouerzi.png');
        $shape->setHeight(103);
        //设置图片宽度
        $shape->setWidth(339);
        //设置图片相对于左上角X位置, 单位像素
        $shape->setOffsetX(10);
        //设置图片相对于左上角Y位置, 单位像素
        $shape->setOffsetY(10);
        //设置图显示状态
        $shape->getShadow()->setVisible(true);
        $shape->getShadow()->setDirection(45);
        $shape->getShadow()->setDistance(10);

        //设置一个文本框
        $shape = $active_slide->createRichTextShape();
        //设置文本框高度, 单位像素
        $shape->setHeight(150);
        //设置文本框宽度, 单位像素
        $shape->setWidth(600);
        //设置文本框相对于左上角X位置, 单位像素
        $shape->setOffsetX(150);
        //设置文本框相对于左上角Y位置, 单位像素
        $shape->setOffsetY(200);
        //设置文本布局位置为水平居中, 垂直居中.
        $shape->getActiveParagraph()->getAlignment()->setHorizontal( \PhpOffice\PhpPresentation\Style\Alignment::HORIZONTAL_CENTER );
        $shape->getActiveParagraph()->getAlignment()->setVertical( \PhpOffice\PhpPresentation\Style\Alignment::VERTICAL_CENTER );
        //设置文本框文本内容. 在中文环境下测试没中文问题. 如果在 e 文环境. 注意要指定支持中文的字体. 否则可能出乱码了.
        $textRun = $shape->createTextRun('欢迎使用 PHPPowerPoint2007');
        //使用字体加粗
        $textRun->getFont()->setBold(true);
        //设置字体尺寸为 38, 这里注意一下文字的大小设置. 前面的文本框的大小是固定的. 如果文字超出的容器会被出容器被排到下面
        $textRun->getFont()->setSize(38);
        //设置文字颜色, 这里是ARGB模式 , 16进制模式, 前面2位为透明度, 后面为RGB值. 这里设置为 blue蓝色
        $textRun->getFont()->setColor( new \PhpOffice\PhpPresentation\Style\Color( 'FFFF0000' ) );

        $objWriter = \PhpOffice\PhpPresentation\IOFactory::createWriter($this->PPT, 'PowerPoint2007');
        //保存文件
        $objWriter->save("/www/tmp/file/ppt/test.ppt");
    }

}