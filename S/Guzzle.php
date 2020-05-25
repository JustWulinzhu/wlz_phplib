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
 * \S\Guzzle::request('http://wlfeng.vip/test/index', 'GET');
 *
 */

namespace S;

require APP_ROOT_PATH . '/Ext/vendor/autoload.php';

class Guzzle
{

    const HTTP_SUCCESS_CODE = 200;
    const HTTP_TIMEOUT = 10;

    /**
     * @param $url
     * @param string $method
     * @param array $params
     * @param array $headers
     * @param string $proxy
     * @param float $time_out
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

        $response = $client->request($method, $url, $options);

        if (self::HTTP_SUCCESS_CODE != $response->getStatusCode()) {
            throw new \Exception('error');
        }

        return $response->getBody()->getContents();
    }

}