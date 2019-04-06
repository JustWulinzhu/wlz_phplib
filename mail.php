<?php

require_once "./PHPMailer/src/PHPMailer.php";
require_once "./PHPMailer/src/SMTP.php";

class Mail {

    private $from_title;
    private $smtp_debug;
    private $host;
    private $smtp_secure;
    private $port ;
    private $charset;
    private $smtp_username;
    private $smtp_password;
    private $from;
    private $nickname;

    public function __construct($config) {
        $this->from_title       = $config['from_title'];
        $this->smtp_debug       = $config['smtp_debug'];
        $this->host             = $config['host'];
        $this->smtp_secure      = $config['smtp_secure'];
        $this->port             = $config['port'];
        $this->charset          = $config['charset'];
        $this->smtp_username    = $config['smtp_username'];
        $this->smtp_password    = $config['smtp_password'];
        $this->from             = $config['from'];
        $this->nickname         = $config['nickname'];
    }

    public function send($to, $title, $content) {
        //实例化PHPMailer核心类
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        //是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
        $mail->SMTPDebug = $this->smtp_debug;
        //使用smtp鉴权方式发送邮件
        $mail->isSMTP();
        //smtp需要鉴权 这个必须是true
        $mail->SMTPAuth = true;
        //链接qq域名邮箱的服务器地址
        $mail->Host = $this->host;
        //设置使用ssl加密方式登录鉴权
        $mail->SMTPSecure = $this->smtp_secure;
        //设置ssl连接smtp服务器的远程服务器端口号，以前的默认是25，但是现在新的好像已经不可用了 可选465或587
        $mail->Port = $this->port;
        //设置smtp的helo消息头 这个可有可无 内容任意
        // $mail->Helo = 'Hello smtp.qq.com Server';
        //设置发件人的主机域 可有可无 默认为localhost 内容任意，建议使用你的域名
        //$mail->Hostname = 'http://zhaozhXXXxg.cn';
        //设置发送的邮件的编码 可选GB2312  据说utf8在某些客户端收信下会乱码
        $mail->CharSet = $this->charset;
        //设置发件人姓名（昵称） 任意内容，显示在收件人邮件的发件人邮箱地址前的发件人姓名
        $mail->FromName = $this->from_title;
        //smtp登录的账号 这里填入字符串格式的qq号即可
        $mail->Username = $this->smtp_username;
        //smtp登录的密码 使用生成的授权码
        $mail->Password = $this->smtp_password;
        //设置发件人邮箱地址 这里填入上述提到的“发件人邮箱”
        $mail->From = $this->from;
        //邮件正文是否为html编码 注意此处是一个方法 不再是属性 true或false
        $mail->isHTML(true);
        //设置收件人邮箱地址 该方法有两个参数 第一个参数为收件人邮箱地址 第二参数为给该地址设置的昵称 不同的邮箱系统会自动进行处理变动 这里第二个参数的意义不大
        $mail->addAddress($to, $this->nickname);
        //添加多个收件人 则多次调用方法即可
        // $mail->addAddress('xxx@163.com','l通知');
        //添加该邮件的主题
        $mail->Subject = $title;
        //添加邮件正文 上方将isHTML设置成了true，则可以是完整的html字符串 如：使用file_get_contents函数读取本地的html文件
        $mail->Body = $content;
        //为该邮件添加附件 该方法也有两个参数 第一个参数为附件存放的目录（相对目录、或绝对目录均可） 第二参数为在邮件附件中该附件的名称
        // $mail->addAttachment('./55.jpg','head.jpg');
        //同样该方法可以多次调用 上传多个附件
        // $mail->addAttachment('./Juery-1.1.0.js','jquery.js');
        return $mail->send();
    }

}





