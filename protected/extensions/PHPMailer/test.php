<?php
/**
* by www.phpddt.com
*/
header("content-type:text/html;charset=utf-8");
ini_set("magic_quotes_runtime",0);
require 'class.phpmailer.php';
try {
	$mail = new PHPMailer(true); 
	$mail->IsSMTP();
	$mail->CharSet='UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
	$mail->SMTPAuth   = true;                  //开启认证
	$mail->Port       = 25;                    
	$mail->Host       = "smtp.exmail.qq.com"; 
	$mail->Username   = "liumc@catail.cn";    
	$mail->Password   = "johncena1992";            
	//$mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could  not execute: /var/qmail/bin/sendmail ”的错误提示
	//$mail->AddReplyTo("648700755@qq.com","liumc");//回复地址
	$mail->From       = "liumc@catail.cn";
	$mail->FromName   = "liumc";
	$to = "987044391@qq.com";
	$mail->AddAddress("18364195378@163.com","liumc");
	$mail->Subject  = "phpmailer测试标题";
	$mail->Body = "<h1>phpmail演示</h1>这是php点点通（<font color=red>www.phpddt.com</font>）对phpmailer的测试内容";
	$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; //当邮件不支持html时备用显示，可以省略
	$mail->WordWrap   = 80; // 设置每行字符串的长度
	$mail->AddAttachment("C:\Users\minchao\Desktop\MCC-E2-SWP-001 (Works at Height) sample SWP.pdf");  //可以添加附件
	$mail->IsHTML(true); 
	$mail->Send();
	echo '邮件已发送';
} catch (phpmailerException $e) {
	echo "邮件发送失败：".$e->errorMessage();
}
?>