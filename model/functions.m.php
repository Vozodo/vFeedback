<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'PHPMailer/src/Exception.php';
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';
require_once "db/config.m.php";

class Functions
{
   public static function get_client_ip()
   {
      $ipaddress = '';
      if (getenv('HTTP_CLIENT_IP'))
         $ipaddress = getenv('HTTP_CLIENT_IP');
      else if (getenv('HTTP_X_FORWARDED_FOR'))
         $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
      else if (getenv('HTTP_X_FORWARDED'))
         $ipaddress = getenv('HTTP_X_FORWARDED');
      else if (getenv('HTTP_FORWARDED_FOR'))
         $ipaddress = getenv('HTTP_FORWARDED_FOR');
      else if (getenv('HTTP_FORWARDED'))
         $ipaddress = getenv('HTTP_FORWARDED');
      else if (getenv('REMOTE_ADDR'))
         $ipaddress = getenv('REMOTE_ADDR');
      else
         $ipaddress = 'UNKNOWN';
      return $ipaddress;
   }

   public static function sendEmailNotification($feedback, $ip, $type, $err = "")
   {

      $mail = new PHPMailer;
      $mail->isSMTP();
      $mail->SMTPDebug = 0; // 0 = off (for production use) - 1 = client messages - 2 = client and server messages
      $mail->Host = EM_HOST; // use $mail->Host = gethostbyname('smtp.gmail.com'); // if your network does not support SMTP over IPv6
      $mail->Port = EM_PORT; // TLS only
      $mail->SMTPSecure = 'tls'; // ssl is depracated
      $mail->SMTPAuth = EM_SMTP;
      $mail->Username = EM_USERNAME;
      $mail->Password = EM_PASSWORD;
      $mail->isHTML(true);
      $mail->setFrom(EM_EMAIL, G_SITENAME);
      $mail->addAddress(G_OWNERMAIL, G_OWNER);


      if (($type == 1) && (EM_NOTIFY === true)) { //For Success Notificaton    
         $mail->Subject = "You received a feedback " . G_OWNER . "!";
         $mail->Body = "<h1>Hi " . G_OWNER .
            "</h1><br />You have received new feedback from " .
            $feedback->getName() . "<br />His Feedback:<br/><b>Title:</b> " .
            $feedback->getTitle() . "<br /><b>Feedback:</b> " . $feedback->getFeedback() .
            "<br/><b>Name:</b> " . $feedback->getName() . "<br/><b>Date:</b> " . $feedback->getDate() .
            "<br/><b>Country:</b> " . $feedback->getCountry() . "<br /><b>IP:</b> " . $ip->getIpaddress() . "<br /><b>User Agent: </b>" . $_SERVER['HTTP_USER_AGENT'];
      } elseif (($type == 2) && (EM_NOTIFY_FALIATURE === true)) { //For Faliture
         $mail->Subject = G_OWNER . ", a feedback was blocked";
         $mail->Body = "<h1>Hi " . G_OWNER .  "</h1><br /> A feedback was blocked, with the following data: " .
            $feedback->getName() . "<br />His Feedback:<br/><b>Title:</b> " .
            $feedback->getTitle() . "<br /><b>Feedback:</b> " . $feedback->getFeedback() .
            "<br/><b>Name:</b> " . $feedback->getName() . "<br/><b>Date:</b> " . $feedback->getDate() .
            "<br/><b>Country:</b> " . $feedback->getCountry() . "<br /><b>IP:</b> " . $ip->getIpaddress() . "<br /><b>User Agent: </b>" . $_SERVER['HTTP_USER_AGENT'] . "<br /><b>Reason:</b> " . $err;
      }

      if (!$mail->send()) {
         return $mail->ErrorInfo;
      } else {
         return true;
      }
   }

   public static function getCountry()
   {

      $ipaddress = Functions::get_client_ip();

      // cURL request
      $curl = curl_init();

      curl_setopt_array($curl, array(
         CURLOPT_URL => "http://v2.api.iphub.info/ip/" . $ipaddress,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_TIMEOUT => 30,
         CURLOPT_CUSTOMREQUEST => "GET",
         CURLOPT_HTTPHEADER => array(
            "x-key: " . IPHUB_APIKEY
         ),
      ));

      $response = json_decode(curl_exec($curl));
      $err = curl_error($curl);

      curl_close($curl);
      if ($err) {
         return "cURL Error :" . $err;
      } else {
         return $response->countryCode;
      }
   }

   public static function isBadIP(string $ip, bool $strict = false)
   {

      $ch = curl_init();
      curl_setopt_array($ch, [
         CURLOPT_URL => "http://v2.api.iphub.info/ip/{$ip}",
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_HTTPHEADER => ["X-Key: " . IPHUB_APIKEY]
      ]);
      try {
         $block = json_decode(curl_exec($ch))->block;
      } catch (Exception $e) {
         throw $e;
      }
      if ($block) {
         if ($strict) {
            return true;
         } elseif (!$strict && $block === 1) {
            return true;
         }
      }
      return false;
   }

   public static function RegEx($feedback)
   {
      if ((preg_match(R_ALLOWED_CHARS, $feedback->getTitle()) != 1) && 
      (preg_match(R_ALLOWED_CHARS, $feedback->getFeedback()) != 1) && 
      (preg_match(R_ALLOWED_CHARS, $feedback->getName()) != 1)) {

         foreach (R_FORBITTEN_WORDS as $value) {

            $regvalue = "/" . $value . "/";

            if ((preg_match($regvalue, $feedback->getTitle()) != 1) && 
            (preg_match($regvalue, $feedback->getFeedback()) != 1) && 
            (preg_match($regvalue, $feedback->getName()) != 1)) {
      
      
               return true;
            } else {
               return false;
            }

            
         }

         return true;
      } else {
         return false;
      }
   }


   public static function AdminLogin($username, $password)
   {

      if (($username == ADMIN_USERNAME) && (password_verify($password, ADMIN_PASSWORD))) {
         $_SESSION['admin'] = true;
         return true;
      } else {
         return false;
      }
   }


   public static function timeAgo($time_ago){

      $time_ago =  strtotime($time_ago);

      $cur_time 	= time();

      $time_elapsed 	= $cur_time - $time_ago;
      $seconds 	= $time_elapsed;
      $minutes 	= round($time_elapsed / 60 );
      $hours 		= round($time_elapsed / 3600);
      $days 		= round($time_elapsed / 86400 );
      $weeks 		= round($time_elapsed / 604800);
      $months 	= round($time_elapsed / 2600640 );
      $years 		= round($time_elapsed / 31207680 );
      // Seconds
      if($seconds <= 60){
         return "$seconds seconds ago";
      }
      //Minutes
      else if($minutes <=60){
         if($minutes==1){
            return "one minute ago";
         }
         else{
            return "$minutes minutes ago";
         }
      }
      //Hours
      else if($hours <=24){
         if($hours==1){
            return "an hour ago";
         }else{
            return "$hours hours ago";
         }
      }
      //Days
      else if($days <= 7){
         if($days==1){
            return "yesterday";
         }else{
            return "$days days ago";
         }
      }
      //Weeks
      else if($weeks <= 4.3){
         if($weeks==1){
            return "a week ago";
         }else{
            return "$weeks weeks ago";
         }
      }
      //Months
      else if($months <=12){
         if($months==1){
            return "a month ago";
         }else{
            return "$months months ago";
         }
      }
      //Years
      else{
         if($years==1){
            return "one year ago";
         }else{
            return "$years years ago";
         }
      }
      }
}
