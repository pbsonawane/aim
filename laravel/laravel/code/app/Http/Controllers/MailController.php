<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Libraries\Maillib;
use App\Http\Requests;
use App\Http\Controllers\Controller;
class MailController extends Controller {
   public function basic_email($to_emails, $subject, $email_body) {

        ini_set('max_execution_time', '3000'); //300 seconds = 5 minutes
        ini_set('max_execution_time', '0'); // for infinite time of execution 

    try {

        $phpmailer = new Maillib();
        if(trim($to_emails) == "")
        {
          $to_emails = "khairnar.amit26@gmail.com";
          $subject = "test";
          $email_body = "test";
        }
        
        $mailresponse = $phpmailer->mailsent($to_emails, $subject, $email_body);
        return $mailresponse;
    } 
    catch (\Exception $e) 
    {
        dd($e);
    }
    catch (\Error $e) 
    {
        dd($e);
    }
      
   }





  /* public function html_email() {
      $data = array('name'=>"Virat Gandhi");
      Mail::send('mail', $data, function($message) {
         $message->to('abc@gmail.com', 'Tutorials Point')->subject
            ('Laravel HTML Testing Mail');
         $message->from('xyz@gmail.com','Virat Gandhi');
      });
      echo "HTML Email Sent. Check your inbox.";
   }
   public function attachment_email() {
      $data = array('name'=>"Virat Gandhi");
      Mail::send('mail', $data, function($message) {
         $message->to('abc@gmail.com', 'Tutorials Point')->subject
            ('Laravel Testing Mail with Attachment');
         $message->attach('C:\laravel-master\laravel\public\uploads\image.png');
         $message->attach('C:\laravel-master\laravel\public\uploads\test.txt');
         $message->from('xyz@gmail.com','Virat Gandhi');
      });
      echo "Email Sent with attachment. Check your inbox.";
   }*/
}