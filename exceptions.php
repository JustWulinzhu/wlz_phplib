<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 18/8/29 下午3:55
 * 自定义异常处理类,继承基本Exception,可在本类中扩展自定义的方法
 */

class Exceptions extends Exception {

    public function __construct($message, $code) {
        parent::__construct($message, $code);
    }

    /**
     * 获取抛出的错误信息
     * @return string
     */
    public function getErrorMessage() {
        $errors =  'errors in ' . $this->getFile() . ' ; lines number ' . $this->getLine() . ' ; error message: ' . '<b style="color:#EA0000">' . $this->getMessage() . '</b>';
        $log_errors = $this->getFile() . ' | ' . $this->getLine() . ' | ' . $this->getMessage();
        Log::getInstance()->error(array($log_errors), 'exception');
        return $errors;
    }

}