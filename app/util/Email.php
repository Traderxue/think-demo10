<?php

namespace app\util;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    function sendEmail($code, $toemail)
    {
        // 实力化类
        $mail = new PHPMailer();
        // 使用SMTP服务
        $mail->isSMTP();
        // 编码格式为utf8，不设置编码的话，中文会出现乱码
        $mail->CharSet = "utf8";
        // 发送方的SMTP服务器地址
        $mail->Host = "smtp.qq.com";
        // 是否使用身份验证
        $mail->SMTPAuth = true;
        // 发送方的163邮箱用户名，就是你申请163的SMTP服务使用的163邮箱
        $mail->Username = "212681712@qq.com";
        // 发送方的邮箱密码，注意用163邮箱这里填写的是“客户端授权密码”而不是邮箱的登录密码
        $mail->Password = "qkreicgbynjabhjc";
        // 使用ssl协议方式
        $mail->SMTPSecure = "ssl";
        // 163邮箱的ssl协议方式端口号是465/994
        $mail->Port = 465;
        // 设置发件人信息，如邮件格式说明中的发件人，这里会显示为Mailer(xxxx@163.com），Mailer是当做名字显示
        $mail->setFrom("212681712@qq.com", "验证码");
        // 设置收件人信息，如邮件格式说明中的收件人，这里会显示为Liang(yyyy@163.com)
        $mail->addAddress($toemail, '');
        // 设置回复人信息，指的是收件人收到邮件后，如果要回复，回复邮件将发送到的邮箱地址
        // $mail->addReplyTo("itlaowen@163.com", "Reply");
        // 设置邮件抄送人，可以只写地址，上述的设置也可以只写地址(这个人也能收到邮件)
        //$mail->addCC("xxx@163.com");
        // 设置秘密抄送人(这个人也能收到邮件)
        //$mail->addBCC("xxx@163.com");
        // 添加附件
        //$mail->addAttachment("bug0.jpg");
        // 邮件标题
        $mail->Subject = "验证码";
        // 邮件正文
        $mail->Body = "您的验证码为:{$code}";
        // 这个是设置纯文本方式显示的正文内容，如果不支持Html方式，就会用到这个，基本无用
        //$mail->AltBody = "This is the plain text纯文本";
        if (!$mail->send()) { // 发送邮件
            return $mail->ErrorInfo;
            // echo "Message could not be sent.";
            // echo "Mailer Error: ".$mail->ErrorInfo;// 输出错误信息
        } else {
            return 1;
        }
    }
}
