<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 19/10/28 下午6:59
 * Email: 18515831680@163.com
 * aes 加密配置
 * iv 注意16位长度
 */

return [

    'common' => [
        'method'    => 'aes128',
        'password'  => 'wlz_aes_common',
        'iv'        => '96b029a736548b5f',
        'options'   =>  0,
    ],

    'aes256' => [
        'method'    => 'aes256',
        'password'  => 'wlz_aes_aes256',
        'iv'        => '16d71e91deba4b63',
        'options'   =>  OPENSSL_RAW_DATA,
    ],

];