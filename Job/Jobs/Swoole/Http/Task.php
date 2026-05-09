<?php
namespace Job\Jobs\Swoole\Http;

use S\Log;

class Task implements \Job\Base {

    /**
     * 异步任务处理：将耗时操作投递到 Task 进程
     * 例如把下单中的 发送短信 发送邮件等耗时的操作投递到 Task 进程处理
     *
     * @param null $argv
     * @return mixed|void
     */
    public function exec($argv = null)
    {
        // 创建 HTTP 服务器，启用 Task 功能
        $http = new \Swoole\Http\Server("0.0.0.0", 9504);

        $http->set([
            'worker_num' => 2,      // 处理请求的 Worker 进程数
            'task_worker_num' => 2,      // 专门处理任务的 Task 进程数
            'daemonize' => false,
            'log_file' => './task.log',
        ]);

        // 处理 HTTP 请求
        $http->on('request', function ($request, $response) use ($http) {
            // 假设需要发送一封邮件（耗时操作）
            $emailData = [
                'to' => 'user@example.com',
                'subject' => 'Welcome',
                'body' => 'Thank you for registering!',
            ];

            // 投递任务到 Task 进程，并传递一个任务 ID（可选）
            $taskId = $http->task(json_encode($emailData));

            // 立即响应客户端，告知任务已接收
            $response->end("任务已投递，任务 ID: {$taskId}");
        });

        // 处理任务（在 Task 进程中执行）
        $http->on('task', function ($server, $taskId, $reactorId, $data) {
            echo "Task {$taskId} 开始处理: {$data}\n";

            // 解析数据
            $email = json_decode($data, true);

            // 模拟发送邮件的耗时操作（例如 sleep 3 秒）
            sleep(3);
            // 这里可以调用实际的邮件发送函数

            // 返回任务执行结果，会传递给 onFinish 事件
            return "邮件已发送给 {$email['to']}";
        });

        // 任务处理完成后的回调（在 Worker 进程中执行）
        $http->on('finish', function ($server, $taskId, $data) {
            echo "Task {$taskId} 完成，结果: {$data}\n";
        });

        $http->start();
    }

}