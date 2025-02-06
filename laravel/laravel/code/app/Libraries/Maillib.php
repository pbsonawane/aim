<?php
namespace App\Libraries;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require  __DIR__ . '/../../vendor/phpmailer/phpmailer/src/Exception.php';
require  __DIR__ . '/../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require  __DIR__ . '/../../vendor/phpmailer/phpmailer/src/SMTP.php';
require  __DIR__ . '/../../vendor/autoload.php';
class Maillib
    {
        function mailsent($to_emails, $subject, $email_body)
        {	

            defined('EMAIL_USERNAME') or define('EMAIL_USERNAME', 'myweb@alerts.esds.co.in');
            //info@sdportal.esdsdev.com
            defined('EMAIL_PASSWORD') or define('EMAIL_PASSWORD', 'EPv6GaNq(A^C!(2mPN');
            //78COXM(9nLO=
            defined('EMAIL_FROM') or define('EMAIL_FROM', 'noreply@esds.co.in');
            defined('EMAIL_REPLY_TO') or define('EMAIL_REPLY_TO', 'noreply@esds.co.in');
            defined('EMAIL_HOST') or define('EMAIL_HOST', 'alerts.esds.co.in');
            //app.server.co.in
            defined('EMAIL_PORT') or define('EMAIL_PORT', 26);
            //587


            $smtp = true;
            $settings = array();
            if (config('enconfig.smtp_status') == "y")
            {
                $smtpserver = EMAIL_HOST;//trim($settings['smtp_host']);
                $smtpport = EMAIL_PORT;//trim($settings['smtp_port']);
                $smtpuser = EMAIL_USERNAME;//trim($settings['smtp_user']);
                $smtppass = EMAIL_PASSWORD;//trim($settings['smtp_pass']);
                $smtpauth = true;//trim($settings['smtp_auth']) == 'TRUE' ? true : false;
                $smtp = true;
            }
            $fromname = 'AIM - Asset Inventory Manager';//eNlight Cloud Services//config('enconfig.email_from');
            $frommail = 'enlight360@alerts.esds.co.in';//config('enconfig.email_from_name');

            $mailads = explode(",", $to_emails);
            if (is_array($mailads) && count($mailads) > 0)
            {
                $mail = new PHPMailer;
                
                if ($smtp)
                {
                    $mail->isSMTP();
                    $mail->SMTPDebug = 0;
                    $mail->Debugoutput = 'html';
                    $mail->Host = $smtpserver;
                    $mail->Port = $smtpport;
                    $mail->SMTPAuth = $smtpauth;
                    $mail->Username = $smtpuser;
                    $mail->Password = $smtppass;
                }
                $mail->setFrom($frommail, $fromname);
                $mail->addReplyTo($frommail, $fromname);
                $mail->Subject = $subject;
                $mail->msgHTML($email_body);
                foreach($mailads as $k => $to)
                {
                    if (trim($to) != "")
                    {
                        $mail->addAddress($to);
                    }
                }
                if ($mail->send() === false)
                    return false;
                else
                    return true;
            }
        }        
    }
?>