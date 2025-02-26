<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include PHPMailer

// Database credentials
$host = 'localhost';
$dbname = 'your_database';
$username = 'your_db_user';
$password = 'your_db_password';

// SMTP credentials
$smtp_host = 'smtp.gmail.com';  // Change according to your SMTP server
$smtp_username = 'sunilvnmt0@gmail.com';
$smtp_password = 'mhpo hlxb thwr kqnr';
$smtp_port = 587; // Typically 587 for TLS, 465 for SSL

try {
    // Connect to database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    if (isset($argv[1])) {
        $emails = [$argv[1]]; // Get the email from the argument
    } else {
        die("No email provided.\n");
    }

    // Fetch emails from database
    // $stmt = $pdo->query("SELECT email FROM users WHERE email IS NOT NULL");
    // $emails = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($emails)) {
        die("No email addresses found.");
    }

    // Initialize PHPMailer
    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = $smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $smtp_username;
        $mail->Password = $smtp_password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $smtp_port;

        // Sender details
        $mail->setFrom($smtp_username, 'Your Name');

        // Email Content
        $mail->isHTML(true);
    

        // Send emails one by one
        foreach ($emails as $email) {
            $mail->addAddress($email);
            $mail->send();
            $mail->clearAddresses(); // Clear recipient for next iteration
        }

        echo "Emails sent successfully!";
    } catch (Exception $e) {
        echo "Email sending failed: {$mail->ErrorInfo}";
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
