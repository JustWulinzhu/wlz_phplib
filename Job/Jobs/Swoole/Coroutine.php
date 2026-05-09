<?php
namespace Job\Jobs\Swoole;

use S\Log;

class Coroutine implements \Job\Base {

    /**
     * 协程示例：并发执行多个任务
     *
     * @param null $argv
     * @return mixed|void
     */
    public function exec($argv = null)
    {
        // 启用协程
        \Swoole\Runtime::enableCoroutine();

        go(function () { // 创建一个协程容器
            echo "协程启动~~ \n";

            // 并发执行三个 HTTP 请求
            $results = [];

            // 使用 WaitGroup 等待所有协程完成
            $wg = new \Swoole\Coroutine\WaitGroup();
            // 设置需要等待的协程数量为 3
            $wg->add(3);

            // 协程 1
            go(function () use ($wg, &$results) {
                // 执行业务逻辑
                $results['baidu'] = file_get_contents('https://www.baidu.com');
                // 在每个协程内部，调用 $wg->done() 表示该协程完成
                $wg->done();
            });

            // 协程 2
            go(function () use ($wg, &$results) {
                $results['google'] = file_get_contents('https://www.php.net');
                $wg->done();
            });

            // 协程 3
            go(function () use ($wg, &$results) {
                $results['github'] = file_get_contents('https://github.com');
                $wg->done();
            });

            // 等待所有协程完成（阻塞当前协程，直到所有 done 被调用，代表每个协程执行完成了）
            $wg->wait();

            // 输出每个请求的内容长度
            foreach ($results as $key => $content) {
                echo "{$key} 长度: " . strlen($content) . " 字节\n";
            }
        });

        // 主程序中的 echo 会在协程执行的同时执行，因为协程是非阻塞的。
        echo "主程序继续执行...\n";
    }

}