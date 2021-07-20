<?php
//Fetch fields.
$from = $_POST["from"];
$to = $_POST["to"];
$cc = $_POST["cc"];
$bcc = $_POST["bcc"];
$subject = $_POST["subject"];
$message = $_POST["message"];
$button = $_POST["push_button"];

//Include required phpmailer php files.
require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Create instance of PHPMailer.
$mail = new PHPMailer;

try{
  $mail->IsSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->Port = 465;
  $mail->SMTPAuth = true;
  $mail->Username = 'viggo.lagerstedtekholm@gmail.com';
  $mail->Password = 'm0deraternaAZ123';
  $mail->SMTPSecure = 'ssl';
  $mail->Body = $message . 'Observera! Detta meddelande är sänt från ett formulär på Internet och avsändaren kan vara felaktig!';

  $mail->isHTML(true);
  $mail->setFrom($from, 'Mailer');
  $mail->addAddress($to, 'Recipient');     //Add a recipient
  $mail->addCC($cc);
  $mail->addBCC($bcc);

  $mail->Subject = $subject;
  $mail->send();

  echo 'Message has been sent';
}catch (Exception $e) {
    echo "Email failed to send. Error details: {$mail->ErrorInfo}";
}

?>
