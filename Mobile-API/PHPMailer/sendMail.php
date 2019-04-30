<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer library files
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

$mail = new PHPMailer;


// SMTP configuration
$mail->isSMTP();
//$mail->Host     = 'spicaworks.com.md-94.webhostbox.net';
$mail->Host     = 'spicaworks.com.md-94.webhostbox.net';
$mail->SMTPAuth = true;
$mail->Username = 'weekinchina@spicaworks.com.md-94.webhostbox.net';
$mail->Password = 'Uck35gz(j0e(';
$mail->SMTPSecure = 'tls';
$mail->Port     = 587;
function sendMail($mail,$from1,$from2,$addAddress,$subject,$content){
$mail->setFrom($from1, $from2);
//$mail->addReplyTo('info@example.com', 'CodexWorld');

// Add a recipient
$mail->addAddress($addAddress);

// Add cc or bcc 
//$mail->addCC('cc@example.com');
//$mail->addBCC('bcc@example.com');

// Email subject
$mail->Subject = $subject;

// Set email format to HTML
$mail->isHTML(true);

// Email body content
$mailContent = $content;
$mail->Body = $mailContent;

// Send email
if(!$mail->send()){
//echo 'Message could not be sent.';
//echo 'Mailer Error: ' . $mail->ErrorInfo;
}else{
print_r($mail->send());
}

}