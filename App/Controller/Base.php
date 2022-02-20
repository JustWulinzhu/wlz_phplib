<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/1/2 上午1:33
 * Email: 18515831680@163.com
 *
 * 控制器基类
 *
 * ps: 所有继承该类的类必须实现index方法，以index方法作为接口的入口
 *
 */

namespace App\Controller;

use S\Tools;

abstract class Base {

    //接口返回格式
    const RESPONSE_FORMAT_JSON = 'json';
    const RESPONSE_FORMAT_HTML = 'html';

    //请求参数
    protected $params = [];
    //smarty
    protected $smarty;
    //参数验证
    protected $verify = true;
    //返回数据
    protected $response = [];
    //返回数据格式，json、html,默认html
    protected $response_format = self::RESPONSE_FORMAT_HTML;

    //默认接口入口方法为index，子类必须实现
    abstract function index($args);

    /**
     * 不允许子类重写
     * Base constructor.
     * @throws \Exception
     */
    public final function __construct()
    {
        $this->params = array_merge(\S\Param::get(), \S\Param::post());
        \S\Log::getInstance()->debug(['api_request_params', json_encode($this->params)]);

        if ($this->verify) { //页面不进行参数验证
            $this->verify($this->params);
        }
        $this->initSmarty();
    }

    /**
     * 签名校验
     * @param $request_params
     */
    private function verify($request_params) {
        try {
            \S\Sign::verify($request_params);
        } catch (\Exception $e) {
            outputJson([], $e->getCode(), $e->getMessage());
        }
    }

    /**
     * 初始化smarty
     * @throws \Exception
     */
    private function initSmarty() {
        $conf = \Config\Conf::getConfig('smarty');

        if (! $this->smarty instanceof \Smarty) {
            $this->smarty = new \Smarty();
        }
        //自定义模板目录
        $this->smarty->template_dir = $conf['template_dir'];
        //自定义编译目录
        $this->smarty->compile_dir = $conf['compile_dir'];
        //自定义变量目录
        $this->smarty->config_dir = $conf['config_dir'];
        //缓存目录
        $this->smarty->cache_dir = $conf['cache_dir'];
        //是否缓存
        $this->smarty->caching = $conf['caching'];
        //设置页面变量左分隔符
        $this->smarty->setLeftDelimiter("{{");
        //设置页面变量右分隔符
        $this->smarty->setRightDelimiter("}}");
    }

}