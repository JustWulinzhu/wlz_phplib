<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 19/10/28 下午5:57
 * Email: 18515831680@163.com
 *
 * Aes是对称加密算法，需要双方使用共同的秘钥，当不需要暴露秘钥时，加密方式推荐使用本方式。
 *
 * 配置文件：config/aes.php
 *
 * 说明
 * @method   加密方式，openssl_get_cipher_methods()，密码学，可获取支持的加密方式。
 *      aes128 使用128位密钥进行加密(128位密钥已可以满足一般的加密需求)
 *      aes256 使用256位密钥进行加密
 * @password 密钥
 *           aes加密算法使用128/256位密钥 长度越长 安全性越高 所需时间也越长
 *           当密钥长度不够时使用0进行补位，长度过长时则截取需要的位数使用
 * @iv       向量值
 *           参与加解密过程，长度固定为16字节, ./createIv.php可以生成随机iv值
 * @options  加密的配置，对同一数据的加解密需要使用一样的配置
 *      0: 默认值 对密文使用base64进行编码后输出，方便存储加密后的数据
 *      OPENSSL_RAW_DATA: 密文使用原编码输出
 *      OPENSSL_ZERO_PADDING: 不采用默认的数据补齐方式 即需要由我们来实现数据的补齐 会对密文使用base64进行编码后输出
 *
 * 加密完成后对数据进行base64_encode，因为aes256加密方式生成的数据为乱码，不利于存储。
 * 解密需要首先对密文进行base64_decode，然后再进行解密。
 */

namespace S\Crypt;

use Config\Conf;

class Aes implements \S\Crypt\CryptInterface {

    /**
     * 数据加密
     * @param $data
     * @param string $default_conf
     * @return mixed|string
     * @throws \Exception
     */
    public static function encrypt($data, $default_conf = self::DEFAULT_KEY) {
        $config = Conf::getConfig("aes/{$default_conf}");

        $encrypted = openssl_encrypt($data, $config['method'], $config['password'], $config['options'], $config['iv']);
        return base64_encode($encrypted);
    }

    /**
     * 解密数据
     * @param $data
     * @param string $default_conf
     * @return false|mixed|string
     * @throws \Exception
     */
    public static function decrypt($data, $default_conf = self::DEFAULT_KEY) {
        $config = Conf::getConfig("aes/{$default_conf}");

        $data = base64_decode($data);
        return openssl_decrypt($data, $config['method'], $config['password'], $config['options'], $config['iv']);
    }

}