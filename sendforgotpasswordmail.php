<?php

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require "vendor/autoload.php";

$mail = new PHPMailer(true);
try {
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;              // Enable verbose debug output
    $mail->isSendmail();                                // Send using SMTP
    $mail->Host       = "";                             // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                           // Enable SMTP authentication
    $mail->Username   = "";                             // SMTP username
    $mail->Password   = "";                             // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted ENCRYPTION_STARTTLS
    $mail->Port       = "";                             // TCP port to connect to
    $mail->AuthType   = "LOGIN";

    $mail->setFrom("sender_email@provider.com", "Api Documentation Tool");
    $mail->addAddress($_POST["emailAddress"]);          // Name is optional
    $mail->addReplyTo("reply_to@provider.com", "Api Documentation Tool");
    $mail->isHTML(true);                                // Set email format to HTML
    $mail->Subject = "Forgot Password Request received";
    $randomOTP = rand();
    session_start();
    $_SESSION["otp"] = $randomOTP;
    $_SESSION["resetfor"] = $_POST["emailAddress"];
    $mail->Body = "Please visit the below link and use the provided OTP to set new password.<br> OTP : ".$randomOTP."<br>Link : <a href='url_to_redirect' target='_blank'>ResetPassword</a>";
    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}