<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Include PHPMailer library
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Require Composer's autoloader
require __DIR__ .'/../../include/vendor/autoload.php'; // Adjust the path if necessary

// Database credentials
$db_host = 'localhost';
$db_user = 'questcom_mutibanken';
$db_pass = 'mutibanken';
$db_name = 'questcom_mutibanken';

// SMTP credentials
$smtp_host = 'mail.muti-bank.enterprises';
$smtp_username = 'support@muti-bank.enterprises';
$smtp_password = 'Pro151622Andrew@';
$smtp_port = 587; // Typically 587 for TLS or 465 for SSL
$smtp_secure = 'tls'; // 'tls' or 'ssl'

// Function to redirect with message
function redirect_with_message($type, $message) {
    header("Location: /account/admin/send_manuel_mail?$type=" . urlencode($message));
    exit();
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize form data
    $receiver_email = $_POST['receiver_email'];
    $selected_language = $_POST['language'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Simple email validation
    if (!filter_var($receiver_email, FILTER_VALIDATE_EMAIL)) {
        redirect_with_message('error', 'Invalid receiver email format.');
    }

    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
        redirect_with_message('error', 'Database connection failed: ' . $conn->connect_error);
    }

    // Query to check if email exists
    $query = "SELECT acct_currency FROM users WHERE acct_email = '$receiver_email'";
    $result = $conn->query($query);

    if ($result->num_rows == 0) {
        // Email does not exist
        $conn->close();
        redirect_with_message('error', 'Receiver email does not exist in the database.');
    } else {
        // Email exists, fetch language
        $row = $result->fetch_assoc();
        $receiver_language = $row['acct_currency'];

        // Compare languages
        if (strcasecmp($selected_language, $receiver_language) != 0) {
            $conn->close();
            redirect_with_message('error', 'Selected language does not match receiver\'s preferred language.');
        }

        // Languages match, proceed to send email
        $conn->close();

        // Initialize PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Server settings
            //$mail->SMTPDebug = 2; // Enable verbose debug output
            $mail->isSMTP();
            $mail->Host       = $smtp_host;
            $mail->SMTPAuth   = true;
            $mail->Username   = $smtp_username;
            $mail->Password   = $smtp_password;
            $mail->SMTPSecure = $smtp_secure;
            $mail->Port       = $smtp_port;

            // Recipients
            $mail->setFrom($smtp_username, 'Muti Bank'); // Adjust the sender name
            $mail->addAddress($receiver_email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = nl2br(htmlspecialchars($message));
            $mail->AltBody = htmlspecialchars($message);

            $mail->send();
            redirect_with_message('success', 'Email has been sent successfully.');
        } catch (Exception $e) {
            redirect_with_message('error', "Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    }
} else {
    // If not a POST request, redirect to the form
    header("Location: index.html");
    exit();
}
