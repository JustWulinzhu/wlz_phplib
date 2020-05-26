<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/5/25 上午10:40
 * Email: 18515831680@163.com
 *
 * Guzzle HTTP请求
 *
 * demo:
 * \S\Http\Guzzle::request('http://wlfeng.vip/test/index', 'GET');
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
        $client = new \GuzzleHttp\Client();

        $options['timeout'] = $time_out;
        if ('GET' === ($method = strtoupper($method))) {
            if ($params) {
                $options['query'] = $params;
            }
        } else {
            $options['form_params'] = $params;
        }
        if ($headers) {
            $options['headers'] = $headers;
        }
        if ($proxy) {
            $options['proxy'] = $proxy;
        }

        Log::getInstance()->debug([__METHOD__, 'request params', $method, $url, json_encode($options)]);
        $response = $client->request($method, $url, $options);
        $result = $response->getBody()->getContents();
        Log::getInstance()->debug([__METHOD__, 'response params', $result]);

        if (self::HTTP_SUCCESS_CODE != ($error_code = $response->getStatusCode())) {
            throw new \Exception("response error code {$error_code}");
        }
        if (empty($result)) {
            throw new \Exception("empty response from {$url}");
        }

        return $result;
    }

}