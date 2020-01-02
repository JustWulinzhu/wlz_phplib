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

    /**
     * Base constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->initSmarty();
    }

    /**
     * 初始化smarty
     * @throws \Exception
     */
    public function initSmarty() {
        $conf = \Config\Conf::getConfig('smarty');

        $this->smarty = new \Smarty();
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