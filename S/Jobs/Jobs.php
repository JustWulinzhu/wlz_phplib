<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 19/11/15 下午4:37
 * Email: 18515831680@163.com
 */

namespace S\Jobs;

class Job {

    public function fork() {
        $pid = pcntl_fork();
        dd($pid);
    }

}