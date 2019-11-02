<?php
/**
 * Created by PhpStorm.
 * Author: wulinzhu
 * Email: linzhu.wu@beebank.com
 * Date: 19/11/03 上午03:00
 * 阿里云mns服务
 */

use AliyunMNS\Client;
use AliyunMNS\Requests\ListQueueRequest;
use AliyunMNS\Requests\SendMessageRequest;
use AliyunMNS\Requests\CreateQueueRequest;
use AliyunMNS\Exception\MnsException;

require_once dirname(dirname(__DIR__)) . "/fun.php";
require_once dirname(dirname(__DIR__)) . "/ext/aliyun-mns-sdk/mns-autoloader.php";

class Mns {

    private $client = '';
    private $access_id = '';
    private $access_key = '';
    private $end_point = '';

    /**
     * Mns constructor.
     * @throws Exception
     */
    public function __construct() {
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
     * @param $queue_name
     * @return array
     */
    public function createQueue($queue_name) {
        try {
            $create_request_obj = new CreateQueueRequest($queue_name);
            $ret = $this->client->createQueue($create_request_obj);
        } catch (MnsException $e) {
            throw new MnsException($e->getMnsErrorCode(), $e->getMessage());
        }

        return Fun::objToArray($ret);
    }

    /**
     * 压入队列
     * @param $queue_name
     * @param $data
     * @return array
     */
    public function push($queue_name, $data) {
        $queue = $this->client->getQueueRef($queue_name);

        try {
            $request = new SendMessageRequest($data);
            $ret = $queue->sendMessage($request);
        } catch (MnsException $e) {
            throw new MnsException($e->getMnsErrorCode(), $e->getMessage());
        }

        return Fun::objToArray($ret);
    }

    /**
     * 消费队列
     * @param $queue_name
     * @return array
     */
    public function pop($queue_name) {
        try {
            //接受消息
            $queue = $this->client->getQueueRef($queue_name);
            $ret = $queue->receiveMessage();
            //删除消息
            $receipt_handle = $ret->getReceiptHandle();
            $this->deleteMsg($queue_name, $receipt_handle);
        } catch (MnsException $e) {
            throw new MnsException($e->getMnsErrorCode(), $e->getMessage());
        }

        return Fun::objToArray($ret);
    }

    /**
     * 删除消费的队列消息
     * @param $queue_name
     * @param $receipt_handle
     * @return array
     */
    public function deleteMsg($queue_name, $receipt_handle) {
        try {
            $queue = $this->client->getQueueRef($queue_name);
            $ret = $queue->deleteMessage($receipt_handle);
        } catch (MnsException $e) {
            throw new MnsException($e->getMnsErrorCode(), $e->getMessage());
        }

        return Fun::objToArray($ret);
    }

}