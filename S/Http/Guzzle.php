<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/5/25 上午10:40
 * Email: 18515831680@163.com
 *
 * Guzzle中文官网：https://guzzle-cn.readthedocs.io/zh_CN/latest/quickstart.html#id7
 *
 * PHP HTTP客户端 GuzzleHTTP
 *
 * @query array
 *      get参数
 *
 * @form_params array
 *      post参数
 *
 * @headers array
 *      header头信息
 *
 * @proxy string
 *      代理信息
 *
 * @upload_file @body resources
 *      要上传的文件内容
 *
 * demo:
 * \S\Http\Guzzle::request('http://wlfeng.vip/test/index', 'GET');
 * \S\Http\Guzzle::request('http://wlfeng.vip/test/index', 'POST', ['name' => '张三', 'upload_file' => file_get_contents('/tmp/a.txt')]);
 *
 * 文件上传请用POST方式
 *
 */

namespace S\Http;

use S\Log;

require APP_ROOT_PATH . '/Ext/vendor/autoload.php';

class Guzzle
{

    const HTTP_SUCCESS_CODE = 200;
    const HTTP_TIMEOUT = 60;

    /**
     * @param $url
     * @param string $method
     * @param array $params
     * @param array $headers
     * @param string $proxy
     * @param int $time_out
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public static function request($url, $method = 'GET', array $params = [], array $headers = [], $proxy = '', $time_out = self::HTTP_TIMEOUT) {
        $options['timeout'] = $time_out;
        if ('GET' === ($method = strtoupper($method))) {
            if ($params) {
                $options['query'] = $params;
            }
        } else {
            if (isset($params['upload_file'])) {
                $options['body'] = $params['upload_file'];
                unset($params['upload_file']);
            }
            $options['form_params'] = $params;
        }
        if ($headers) {
            $options['headers'] = $headers;
        }
        if ($proxy) {
            $options['proxy'] = $proxy;
        }

        $response = (new \GuzzleHttp\Client())->request($method, $url, $options);
        $result = $response->getBody()->getContents();

        if (self::HTTP_SUCCESS_CODE != ($error_code = $response->getStatusCode())) {
            throw new \Exception($response->getReasonPhrase(), $error_code);
        }
        if (empty($result)) {
            throw new \Exception("empty response from {$url}");
        }

        return $result;
    }

}