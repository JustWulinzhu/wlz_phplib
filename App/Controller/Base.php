<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/1/2 上午1:33
 * Email: 18515831680@163.com
 *
 * 控制器基类
 *
 */

namespace App\Controller;

class Base {

    protected $smarty;
    protected $verify = true;

    /**
     * 不允许子类重写
     * Base constructor.
     * @throws \Exception
     */
    public final function __construct()
    {
        if ($this->verify) { //页面不进行参数验证
            $this->verify();
        }
        $this->initSmarty();
    }

    /**
     * 签名校验
     * @throws \Exception
     */
    private function verify() {
        $request_params = array_merge(\S\Param::get(), \S\Param::post());
        \S\Log::getInstance()->debug(['api_request_params', json_encode($request_params)]);
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
    }

}