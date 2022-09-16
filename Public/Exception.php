<?php
/**
 * 自定义顶层异常类，所有未捕获的异常都会在这里被捕获
 * @param $exception
 */
function finalException($exception)
{
    echo "<b>Uncaught Exception：</b> " . $exception->getMessage() . "<br/>";
    echo "<b>Uncaught Exception File：</b> " . $exception->getFile() . "<b> in line </b>" . $exception->getLine();
    \S\Log::getInstance()->debug(["Uncaught Exception：" . $exception->getMessage() . " | " . "Uncaught Exception File：" . $exception->getFile() . " in line " . $exception->getLine()], 'exception');
}

set_exception_handler('finalException');