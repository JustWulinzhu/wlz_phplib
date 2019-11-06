<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 19/10/27 下午11:53
 * Email: 18515831680@163.com
 *
 * Rsa加密方式，非对称加密，拥有两个秘钥，公钥、私钥；
 * 公钥、私钥是以一对形式存在，缺一不可，使用公钥进行加密的数据，只能使用私钥才能解开。公钥需要发放给客户端进行加密使用，
 * 私钥保存在服务端进行解密，这也就需要保护私钥的私密性，防止泄露私钥，私钥泄露，任何拥有私钥的服务都可以对密文进行解密。
 *
 * 在需要暴露加密秘钥时，请采用Rsa对数据进行加密；
 * Example：在前端使用公钥对用户的输入加密，防止直接以明文来进行传输，后端再使用私钥对密文解密
 *
 * 配置文件： config/rsa.php
 * <code>
 * 'common' => [
 *      'public_key' => 'xxx',
 *      'private_key' => 'xxx',
 * ]
 * </code>
 *
 * @public_key 公钥
 * @private_key 私钥
 *
 * 加密解密参数
 * @padding Rsa加解密时的填充模式,下面是几种常用的填充模式介绍
 *      OPENSSL_PKCS1_PADDING:   默认值 最常用的填充模式 一次加密中明文长度需要比模长度短至少11个字节
 *      OPENSSL_NO_PADDING：     不填充
 *      RSA_PKCS1_OAEP_PADDING:  一次加密中明文长度需要比模长度短至少41个字节
 * 对数据对加解密需要约定一样的填充方式。
 *
 * 公钥私钥生成方式：
 * 下载开源RSA密钥生成工具openssl（通常Linux系统都自带该程序），解压缩至独立的文件夹，进入其中的bin目录，执行以下命令：
 * 1> openssl genrsa -out rsa_private_key.pem 1024
 * 2> openssl pkcs8 -topk8 -inform PEM -in rsa_private_key.pem -outform PEM -nocrypt -out private_key.pem
 * 3> openssl rsa -in rsa_private_key.pem -pubout -out rsa_public_key.pem
 *
 * 第一条命令生成原始 RSA私钥文件 rsa_private_key.pem
 * 第二条命令将原始 RSA私钥转换为 pkcs8格式
 * 第三条生成RSA公钥 rsa_public_key.pem
 * 上面几个就可以看出：通过私钥能生成对应的公钥。
 *
 */

namespace S\Crypt;

use Config\Conf;

class Rsa implements \S\Crypt\CryptInterface {

    /**
     * 获取私钥
     * @param $rsa_config
     * @return false|resource
     * @throws \Exception
     */
    private static function getPrivateKey($rsa_config) {
        $rsa_content = Conf::getConfig("rsa/{$rsa_config}");
        return openssl_pkey_get_private($rsa_content['private_key']);
    }

    /**
     * 获取公钥
     * @param $rsa_config
     * @return false|resource
     * @throws \Exception
     */
    private static function getPublicKey($rsa_config) {
        $rsa_content = Conf::getConfig("rsa/{$rsa_config}");
        return openssl_pkey_get_public($rsa_content['public_key']);
    }

    /**
     * 数据加密
     * @param $data
     * @param string $rsa_config
     * @param int $padding
     * @return mixed|string
     * @throws \Exception
     */
    public static function encrypt($data, $rsa_config = self::DEFAULT_KEY, $padding = OPENSSL_PKCS1_PADDING) {
        $public_key = self::getPublicKey($rsa_config);

        $err_msg = '';
        $crypted = '';
        if (! openssl_public_encrypt($data, $crypted, $public_key, $padding)) {
            while ($msg = openssl_error_string()) {
                $err_msg .= $msg . '\n';
            }
            throw new \Exception($err_msg);
        }

        return base64_encode($crypted);
    }

    /**
     * 数据解密
     * @param $data
     * @param string $rsa_config
     * @param int $padding
     * @return mixed|string
     * @throws \Exception
     */
    public static function decrypt($data, $rsa_config = self::DEFAULT_KEY, $padding = OPENSSL_PKCS1_PADDING) {
        $data = base64_decode($data);
        $private_key = self::getPrivateKey($rsa_config);

        $err_msg = '';
        $decrypted = '';
        if (! openssl_private_decrypt($data, $decrypted, $private_key, $padding)) {
            while ($msg = openssl_error_string()) {
                $err_msg .= $msg . '\n';
            }
            throw new \Exception($err_msg);
        }

        return $decrypted;
    }

}