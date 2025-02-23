<?php
require '../config.php';
require '../vendor/autoload.php';
use Twilio\Rest\Client;
use PHPMailer\PHPMailer\PHPMailer;

$twilioSID = "YOUR_TWILIO_SID";
$twilioToken = "YOUR_TWILIO_TOKEN";
$twilioNumber = "YOUR_TWILIO_NUMBER";

$stmt = $conn->query("SELECT phone FROM queue ORDER BY timestamp ASC LIMIT 1");
$customer = $stmt->fetch_assoc();

if ($customer) {
    // Send SMS
    $client = new Client($twilioSID, $twilioToken);
    $client->messages->create($customer['phone'], [
        "From" => $twilioNumber,
        "Body" => "You're next in the queue! Please proceed to the counter."
    ]);

    // Send Email
    $mail = new PHPMailer();
    $mail->setFrom("your-email@example.com", "Queue System");
    $mail->addAddress("customer-email@example.com");
    $mail->Subject = "Queue Update";
    $mail->Body = "You're next in the queue! Please proceed to the counter.";
    $mail->send();
}
?>
