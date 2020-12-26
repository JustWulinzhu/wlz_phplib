<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 20/12/11 上午10:51
 */

namespace S\Soap;

use S\Tools;

class Server
{

    protected $class = null;

    public function __construct($class) {
        $this->class = $class;
    }

    public function request() {
        $wsdl_file = (new \S\Soap\Wsdl('Test', 'Test'))->getWSDL();
        Tools::write('/tmp/a', $wsdl_file);
        var_dump($wsdl_file);

        $soap_server = new \SoapServer($wsdl_file);

        $soap_server->setClass($this->class);
        $soap_server->handle();
    }

}