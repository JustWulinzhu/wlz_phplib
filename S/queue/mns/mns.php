<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 19/11/3 下午4:51
 * Email: 18515831680@163.com
 *
 * 阿里云mns服务
 *
 * $mns = new \Queue\Mns\Mns();
 *
 * 创建队列：
 * $mns->createQueue($queue_name);
 * 入栈：
 * $mns->push($queue_name, $data);
 * 出栈：
 * $data = $mns->pop($queue_name);
 * if ($data) {
 *      //执行业务代码
 * }
 *
 */

namespace S\Queue\Mns;

use S\Log;
use Config\Conf;
use AliyunMNS\Client;
use AliyunMNS\Requests\ListQueueRequest;
use AliyunMNS\Requests\SendMessageRequest;
use AliyunMNS\Requests\CreateQueueRequest;
use AliyunMNS\Exception\MnsException;

require_once dirname(dirname(__DIR__)) . "/ext/aliyun-mns-sdk/mns-autoloader.php";

class Mns
{

    private $client = '';
    private $access_id = '';
    private $access_key = '';
    private $end_point = '';

    /**
     * Mns constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $config = Conf::getConfig('mns/mns');
        $this->access_id = $config['access_id'];
        $this->access_key = $config['access_key'];
        $this->end_point = $config['end_point'];

        if (empty($this->client)) {
            $this->client = new Client($this->end_point, $this->access_id, $this->access_key);
        }
    }

    /**
     * 创建队列
     * @param string $queue_name
     * @return array
     */
    public function createQueue(string $queue_name)
    {
        try {
            $create_request_obj = new CreateQueueRequest($queue_name);
            $ret = $this->client->createQueue($create_request_obj);
        } catch (MnsException $e) {
            throw new MnsException($e->getCode(), $e->getMessage());
        }

        return [
            'status'     => $ret->getStatusCode(),
            'is_success' => $ret->isSucceed(),
        ];
    }

    /**
     * 压入队列
     * @param string $queue_name
     * @param string $data
     * @return array
     * @throws \Exception
     */
    public function push(string $queue_name, string $data)
    {
        try {
            $queue = $this->client->getQueueRef($queue_name);
            $request = new SendMessageRequest($data);
            $ret = $queue->sendMessage($request);
        } catch (MnsException $e) {
            Log::getInstance()->warning(['mns_push_error', $queue_name, json_encode($data), $e->getCode(), $e->getMessage()]);
            throw new MnsException($e->getCode(), $e->getMessage());
        }

        return [
            'status'        => $ret->getStatusCode(),
            'is_success'    => $ret->isSucceed(),
            'message_id'    => $ret->getMessageId(),
        ];
    }

    /**
     * 消费队列
     * @param string $queue_name
     * @return array
     * @throws \Exception
     */
    public function pop(string $queue_name)
    {
        try {
            //接受消息
            $queue = $this->client->getQueueRef($queue_name);
            $ret = $queue->receiveMessage();
            //删除消息
            $receipt_handle = $ret->getReceiptHandle();
            $this->deleteMsg($queue_name, $receipt_handle);
        } catch (MnsException $e) {
            Log::getInstance()->warning(['mns_pop_error', $queue_name, $e->getCode(), $e->getMessage()]);
            throw new MnsException($e->getCode(), $e->getMessage());
        }

        return [
            'body'          => $ret->getMessageBody(),
            'status'        => $ret->getStatusCode(),
            'is_success'    => $ret->isSucceed(),
            'message_id'    => $ret->getMessageId(),
        ];
    }

    /**
     * 删除消费的队列消息
     * @param $queue_name
     * @param $receipt_handle
     * @return array
     * @throws \Exception
     */
    private function deleteMsg($queue_name, $receipt_handle)
    {
        try {
            $queue = $this->client->getQueueRef($queue_name);
            $ret = $queue->deleteMessage($receipt_handle);
        } catch (MnsException $e) {
            Log::getInstance()->warning(['mns_deleteMsg_error', $queue_name, $e->getCode(), $e->getMessage()]);
            throw new MnsException($e->getCode(), $e->getMessage());
        }

        return [
            'status'        => $ret->getStatusCode(),
            'is_success'    => $ret->isSucceed(),
        ];
    }

}