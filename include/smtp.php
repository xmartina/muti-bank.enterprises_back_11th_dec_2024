<?php
require __DIR__. '/../include/vendor/autoload.php';


use PHPMailer\PHPMailer\PHPMailer;

// MESSAGE & EMAIL CONFIGURATION FOR SCRIPT
class message{
    private $conn;
    public function send_mail($email, $message, $subject){

        $mail = new PHPMailer();
        //SMTP Settings
        //$mail->isSMTP();
        $mail->isMail();
        $mail->Host = "muti-bank.enterprises"; // Change Email Host
        $mail->SMTPAuth = true;
        $mail->Username = "support@muti-bank.enterprises"; // Change Email Address
        $mail->Password = 'Pro151622Andrew@'; // Change Email Password
        $mail->Port = 587; //587
        $mail->SMTPSecure = "ssl"; //tls

        //Email Settings
        $mail->isHTML(true);
        $mail->setFrom('support@muti-bank.enterprises','Multi Bank'); // Change
        $mail->addAddress($email);
        $mail->AddReplyTo("support@muti-bank.enterprises", "Multi Bank"); // Change
        $mail->Subject = $subject;
        $mail->MsgHTML($message);
        $mail->Send();


    }

}
