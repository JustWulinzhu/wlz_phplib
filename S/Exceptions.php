<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 18/8/29 下午3:55
 * 自定义异常处理类,继承基本Exception,可在本类中扩展自定义的方法
 */

namespace S;

class Exceptions extends \Exception {

    /**
     * Exceptions constructor.
     * @param $message
     * @param int $code
     * @throws \Exception
     */
    public function __construct($message, $code = 0) {
        //记录抛错文件及代码行数
        Log::getInstance()->error(array("exception_msg: " . $message, "exception_code: " . $code, $this->getFile(), $this->getLine()), 'exceptions');

        parent::__construct($message, $code);
    }

}