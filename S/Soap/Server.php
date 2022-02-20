<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 20/12/11 上午10:51
 *
 * Soap 服务端
 *
 */

namespace S\Soap;

class Server
{

    protected $class = null;
    protected $service = null;

    /**
     * Server constructor.
     * @param $class
     * @param $service
     * @throws \S\Exceptions
     */
    public function __construct($class, $service) {
        if (empty($class)) throw new \S\Exceptions('empty class.');
        if (empty($service)) throw new \S\Exceptions('empty service.');

        $this->class = $class;
        $this->service = $service;
    }

    /**
     * @throws \ReflectionException
     */
    public function request() {
        ini_set("soap.wsdl_cache_enabled", "0");
        libxml_disable_entity_loader(false);

        $wsdl_file = (new \S\Soap\Wsdl($this->class, $this->service))->getWSDL();

        $soap_server = new \SoapServer('/www/image/Id.wsdl');

        $soap_server->setClass($this->class);
        $soap_server->handle();
    }

}