<?php

	include_once("mail.php");

	$config = array(
			'from_title' => '武林柱邮件系统',
			'smtp_debug' => 1,
			'host' => 'smtp.qq.com',
			'smtp_secure' => 'ssl',
			'port' => 465,
			'charset' => 'UTF-8',
			'smtp_username' => '599075133@qq.com',
			'smtp_password' => 'zodmkymshkpnbeaf',
			'from' => '599075133@qq.com',
			'nickname' => '',
		);

	$res = (new Mail($config))->send('18515831680@163.com', '测试aaa', '测试bbb');
	var_dump($res);

?>