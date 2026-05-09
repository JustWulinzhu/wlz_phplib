<?php

//打开错误提示
ini_set("display_errors", "Off");
//显示所有错误类型
ini_set("error_reporting",E_ALL);
//注册一个会在php中止时执行的函数
register_shutdown_function("myErrors");
//输出错误信息
function myErrors() {
    if ($errors = error_get_last()) {
        $message = '';
        $message .= "错误信息：" . "<b style='color: #d43f3a'>" . $errors['message'] . "</b>"."\n<br/>";
        $message .= "出错文件：" . "<b style='color: #d43f3a'>" . $errors['file'] . "</b>"."\n<br/>";
        $message .= "出错行数：" . "<b style='color: #d43f3a'>" . $errors['line']  ."</b>"."\n<br/>";
        echo $message;
    }
}