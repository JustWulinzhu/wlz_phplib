<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 19/10/28 下午3:45
 * Email: 18515831680@163.com
 * 加密类统一规范接口，此类中的方法子类必须实现
 */

namespace S\Crypt;

interface CryptInterface {

    const DEFAULT_KEY = 'common'; //默认配置key

    /**
     * 统一加密接口
     * @param $data
     * @return mixed
     */
    public static function encrypt($data);

    /**
     * 统一解密接口
     * @param $data
     * @return mixed
     */
    public static function decrypt($data);

}