<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/6/4 下午5:29
 * Email: 18515831680@163.com
 *
 * crontab 8 * * * *
 *
 * /usr/local/php/bin/php /www/wlz_phplib/Job/Job.php Job_Jobs_Mail_Wife
 *
 */

namespace Job\Jobs\Mail;

class Wife implements \Job\Base
{

    /**
     * @param null $argv
     * @return bool|mixed
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function exec($argv = null)
    {
        $date = date('Y-m-d');
        $start_date = '2008-09-01';
        $days = (strtotime($date) - strtotime($start_date)) / 86400;

        $content = "<p style='color: #de3b8a; font-size: large'>我爱你，今天是{$date}，爱你的第{$days}天</p>";
        return (new \S\Mail())->send('790793352@qq.com', '亲爱的老婆', $content);
    }

}