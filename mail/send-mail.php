<?php 

Email::SendMail("wtfavaro@hotmail.com", "William Favaro", "Hello!", "<b>Does</b> markup work?", "Does markup work?");

class Email {

  public static function SendMail($sendToAddress, $sendToName = "", $subject, $body, $altbody, $fromAddress = "support@mindstack.co", $fromName = "Mindstack Inc."){

    require_once("../../PHPMailer/class.phpmailer.php");
    require_once("../../PHPMailer/class.smtp.php");

    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';            // set mailer to use SMTP
    $mail->Host = "smtp.live.com";  // specify main and backup server
    $mail->SMTPAuth = true;     // turn on SMTP authentication
    $mail->Username = $fromAddress;  // SMTP username
    $mail->Password = "mindmap12345"; // SMTP password

    $mail->From = $fromAddress;
    $mail->FromName = $fromName;
    $mail->AddAddress($sendToAddress, $sendToName);
    //$mail->AddAddress("ellen@example.com");                  // name is optional
    $mail->AddReplyTo($fromAddress, $fromName);

    $mail->WordWrap = 50;                                 // set word wrap to 50 characters
    //$mail->AddAttachment("/var/tmp/file.tar.gz");         // add attachments
    //$mail->AddAttachment("/tmp/image.jpg", "new.jpg");    // optional name
    $mail->IsHTML(true);                                  // set email format to HTML

    $mail->Subject = $subject;
    $mail->Body    = $body;
    $mail->AltBody = $altbody;

    // Try to send the email message. If it fails, then we
    // return false.
    if(!$mail->Send())  {
       echo "Message could not be sent. <p>";
       echo "Mailer Error: " . $mail->ErrorInfo;
       return false;
       exit;
    }

    return true;

  }
}

?>
