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
        //恋爱日期
        $date = date('Y-m-d');
        $start_date = '2008-09-01';
        $days = (strtotime($date) - strtotime($start_date)) / 86400;

        //结婚日期
        $marry_day = '2020-10-29';
        $marryed_days = (strtotime($date) - strtotime($marry_day)) / 86400 + 1;

        $content = "<p style='color: #de3b8a; font-size: large'>我爱你，今天是{$date}，爱你的第{$days}天。我们结婚的第{$marryed_days}天。</p>";

        $i = 0;
        while ($i < 3) {
            $ret = (new \S\Mail())->send('790793352@qq.com', '亲爱的老婆', $content);
            if ($ret) break;
            $i++;
        }

        return true;
    }

}