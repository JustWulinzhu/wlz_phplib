<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 19/12/03 下午4:37
 * Email: 18515831680@163.com
 */

namespace S\Daemon;

use S\Fun;

class Thread
{

    const DAEMON_CONFIG = "/www/tmp/daemon/config";
    const DAEMON_PID = "/www/tmp/daemon/pid";

    private $pids = [];
    public static $sleep_seconds = 5; //默认等待时间5s

    /**
     * @throws \Exception
     */
    public function run() {
        $pid = pcntl_fork(); //在当前进程中创建子进程

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
            exit();
        }

        //创建一个新的会话,使新进程成为一个新会话的“领导者”,脱离原来的控制终端
        //说白了就是和之前在终端执行的php jobs.php脱离关系,新建了一个进程,不受终端的管理
        $sid = posix_setsid();
        if ($sid == -1) {
            throw new \Exception('sid error');
        }

        // 修改当前进程的工作目录,由于子进程会继承父进程的工作目录,修改工作目录以释放对父进程工作目录的占用。
        chdir("/");

        /**
         * 通过上一步, 我们创建了一个新的会话组长, 进程组长, 且脱离了终端, 但是会话组长可以申请重新打开一个终端, 为了避免
         * 这种情况, 我们再次创建一个子进程, 并退出当前进程, 这样运行的进程就不再是会话组长
         */

        if (defined('STDIN'))   fclose(STDIN);
        if (defined('STDOUT'))  fclose(STDOUT);
        if (defined('STDERR'))  fclose(STDERR);

        $this->_fork();

    }

    /**
     * fork进程
     * @param int $process_num 进程数
     * @throws \Exception
     */
    public function _fork() {
        $process_arr = $this->getDaemonConfig();

        foreach ($process_arr as $process) {
            $obj = new $process['namespace'];
            $namespace = trim(str_replace("\\", "_", $process['namespace']), "Job_");

            for ($i = 0; $i < $process['process_num']; ++$i) {
                $pid = pcntl_fork();
                if ($pid == -1) {
                    throw new \Exception('创建进程失败');
                }
                if ($pid > 0) {
                    pcntl_wait($status, WNOHANG);
                } else {
                    $this->setPid(posix_getpid());

                    while (true) {
                        //设置进程别名
                        cli_set_process_title("/usr/local/php/bin/php /www/wlz_phplib/Job/Job.php {$namespace}");
                        //执行脚本
                        $obj->exec();
                        //设置睡眠时间
                        if (isset($obj::$sleep_seconds)) {
                            sleep($obj::$sleep_seconds);
                        } else {
                            sleep(self::$sleep_seconds);
                        }
                    }
                }
            }
        }
    }

    /**
     * kill -9所有进程
     * @return bool
     * @throws \Exception
     */
    public function killAll() {
        $pids = $this->getPid();
        foreach ($pids as $pid) {
            posix_kill($pid, SIGKILL);
        }
        //删除pid文件
        $pid_path = self::DAEMON_PID . DIRECTORY_SEPARATOR . "pids.txt";
        if (file_exists($pid_path)) {
            unlink($pid_path);
        }
        //删除进程配置文件
        $files = Fun::scanDir(self::DAEMON_CONFIG);
        foreach ($files as $file) {
            unlink(self::DAEMON_CONFIG . DIRECTORY_SEPARATOR . $file);
        }

        return true;
    }

    /**
     * pid落地
     * @param $pid
     * @return false|int
     */
    public function setPid($pid) {
        $content = $pid . "\n";
        if (! file_exists(self::DAEMON_PID)) {
            mkdir(self::DAEMON_PID, 0777, true);
        }
        $file_name = self::DAEMON_PID . DIRECTORY_SEPARATOR . "pids.txt";

        return file_put_contents($file_name, $content, FILE_APPEND);
    }

    /**
     * 获取pid
     * @return array
     */
    public function getPid() {
        $file_name = self::DAEMON_PID . DIRECTORY_SEPARATOR . "pids.txt";
        if (file_exists($file_name)) {
            $pids = file_get_contents($file_name);
            $pids = explode("\n", $pids);
            return array_filter($pids);
        }

        return [];
    }

    /**
     * 获取守护进程配置
     * @return array
     * @throws \Exception
     */
    public function getDaemonConfig() {
        $files = Fun::scanDir(self::DAEMON_CONFIG);

        $configs = [];
        foreach ($files as $file) {
            $file_path = self::DAEMON_CONFIG . DIRECTORY_SEPARATOR . $file;
            if (! file_exists($file_path)) {
                throw new \Exception('daemon config not found');
            }

            $content = file_get_contents($file_path);
            $content_arr = array_filter(explode("\n", $content));
            $config = [];
            foreach ($content_arr as $value) {
                $value = explode(",", $value);
                $config = [
                    'namespace' => $value[0],
                    'process_num' => $value[1],
                ];
            }
            $configs[] = $config;
        }

        return $configs;
    }

    /**
     * 守护进程配置
     * @param $namespace
     * @param $process_num
     * @return false|int
     */
    public function setDaemonConfig($namespace, $process_num) {
        $file_name = trim(str_replace("\\", "_", $namespace), "_") . ".txt";
        $content = $namespace . "," . $process_num ."\n";
        if (! file_exists(self::DAEMON_CONFIG)) {
            mkdir(self::DAEMON_CONFIG, 0777, true);
        }
        $file_path = self::DAEMON_CONFIG . DIRECTORY_SEPARATOR . $file_name;
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        return file_put_contents($file_path, $content, FILE_APPEND);
    }

}