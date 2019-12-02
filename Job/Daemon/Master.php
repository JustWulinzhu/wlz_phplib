<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 19/11/15 下午4:37
 * Email: 18515831680@163.com
 *
 * master守护进程
 *
 */

namespace Job\Daemon;

use S\Log;

class Master implements \Job\Base
{

    /**
     * @param null $argv
     * @return mixed|void
     * @throws \Exception
     */
    public function exec($argv = null) {
        $ppid = posix_getpid(); //当前进程pid
        Log::getInstance()->debug(['current pid', $ppid]);
        $pid = pcntl_fork(); //在当前进程中创建子进程
        Log::getInstance()->debug(['first pid', $pid]);

        /**
         * 父进程和子进程都会执行下面的代码
         * 父进程返回子进程pid
         * 子进程返回0
         * 创建失败返回-1
         */
        if ($pid == -1) { //创建失败
            throw new \Exception('第一次创建进程失败');
        }
        if ($pid > 0) { //父进程
            Log::getInstance()->debug(['第一次父进程退出']);
            exit();
        }

        //创建一个新的会话,使新进程成为一个新会话的“领导者”,脱离原来的控制终端
        //说白了就是和之前在终端执行的php jobs.php脱离关系，新建了一个进程,不受终端的管理
        $sid = posix_setsid();
        Log::getInstance()->debug(['sid', $sid]);
        if ($sid == -1) {
            throw new \Exception('sid error');
        }

        // 修改当前进程的工作目录,由于子进程会继承父进程的工作目录,修改工作目录以释放对父进程工作目录的占用。
        chdir("/");

        /**
         * 通过上一步，我们创建了一个新的会话组长，进程组长，且脱离了终端，但是会话组长可以申请重新打开一个终端，为了避免
         * 这种情况，我们再次创建一个子进程，并退出当前进程，这样运行的进程就不再是会话组长。
         */

        if (defined('STDIN'))   fclose(STDIN);
        if (defined('STDOUT'))  fclose(STDOUT);
        if (defined('STDERR'))  fclose(STDERR);

        for ($i = 0; $i < 5; ++$i) {
            $pid = pcntl_fork();
            if ($pid == -1) {
                throw new \Exception('第二次创建进程失败');
            }
            if ($pid > 0) {
                pcntl_wait($status, WNOHANG);
            } else {
                while (true) {
                    (new Test())->exec();
                    sleep(1);
                }
            }
        }

    }

}