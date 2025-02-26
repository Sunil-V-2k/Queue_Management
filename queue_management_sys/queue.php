<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json"); // Ensure JSON response

require 'E:/Xampp/htdocs/Final_foss/phpmailer/vendor/autoload.php'; // PHPMailer
require 'db_config.php'; // Database credentials

// header("Content-Type: application/json");

if (!$pdo) {
    echo json_encode(["error" => "Database connection failed."]);
    exit;
}



$pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$method = $_SERVER['REQUEST_METHOD'];

if ($method == "GET") {
    // Fetch queue data
    // $stmt = $pdo->query("SELECT id, name, phone, email, timestamp FROM queue ORDER BY id ASC");
    // echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

    try {
        $stmt = $pdo->query("SELECT id, name, phone, email, timestamp FROM queue ORDER BY id ASC");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } catch (Exception $e) {
        echo json_encode(["error" => "Failed to fetch queue data"]);
    }

} elseif ($method == "POST") {
    // Read JSON input
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!isset($data['name'], $data['phone'], $data['email'])) {
        echo json_encode(["error" => "Missing fields"]);
        exit;
    }

    $name = htmlspecialchars($data['name']);
    $phone = htmlspecialchars($data['phone']);
    $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);

    if (!$email) {
        echo json_encode(["error" => "Invalid email"]);
        exit;
    }

    // Insert customer into queue
    $stmt = $pdo->prepare("INSERT INTO queue (name, phone, email) VALUES (?, ?, ?)");
    $stmt->execute([$name, $phone, $email]);

    // Get position in queue
    $positionStmt = $pdo->query("SELECT COUNT(*) FROM queue");
    $position = $positionStmt->fetchColumn(); // Gets total customers in queue

    // Send email notification with position
    if (sendEmail($email, $name, $phone, $position)) {
        echo json_encode(["message" => "Customer added and email sent"]);
    } else {
        echo json_encode(["error" => "Customer added but email failed"]);
    }

} elseif ($method == "DELETE") {
    // Serve the next customer
    $stmt = $pdo->query("SELECT id FROM queue ORDER BY id ASC LIMIT 1");
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($customer) {
        $deleteStmt = $pdo->prepare("DELETE FROM queue WHERE id = ?");
        $deleteStmt->execute([$customer['id']]);
        echo json_encode(["message" => "Customer served"]);
    } else {
        echo json_encode(["error" => "No customers in queue"]);
    }
}

function sendEmail($toEmail, $name, $phone, $position) {
    $mail = new PHPMailer(true);

    try {
        // Enable verbose debug output
        $mail->SMTPDebug = 2;  // 0 = off, 1 = commands, 2 = detailed debug

        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Change this for other providers (e.g., Outlook, Yahoo)
        $mail->SMTPAuth = true;
        $mail->Username = 'sunilvnmt0@gmail.com';  // Your SMTP email
        $mail->Password = 'mhpo hlxb thwr kqnr';  // Your SMTP password or app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use TLS encryption
        $mail->Port = 587; // Typically 587 for TLS, 465 for SSL

        // Email Details
        $mail->setFrom('sunilvnmt0@gmail.com', 'Queue System');
        $mail->addAddress($toEmail, $name);
        $mail->isHTML(true);

        // $mail->Subject = " Queue Confirmation - Your Position: $position";
        // $mail->Body = "
        // <html>
        // <head>
        //     <style>
        //         body { font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; padding: 20px; }
        //         .container { background: white; max-width: 600px; margin: 0 auto; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); }
        //         h2 { color: #2c3e50; }
        //         p { font-size: 16px; line-height: 1.5; }
        //         .footer { font-size: 12px; color: #777; text-align: center; margin-top: 20px; }
        //         .btn { background: #28a745; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: bold; }
        //         .btn:hover { background: #218838; }
        //     </style>
        // </head>
        // <body>
        //     <div class='container'>
        //         <h2>✅ You Have Been Added to the Queue!</h2>
        //         <p><strong>Name:</strong> $name</p>
        //         <p><strong>Phone:</strong> $phone</p>
        //         <p><strong>Your Position in Queue:</strong> $position</p>
        //         <p>⏳ Estimated Wait Time: <strong>" . ($position * 8) . " minutes</strong></p>
        //         <p>📢 We will notify you when it's your turn.</p>
        //         <p><a href='http://localhost/your_project/queue_status.php' class='btn'>Check Your Queue Status</a></p>
        //         <hr>
        //         <p class='footer'>📍 Queue Management System | Contact: support@yourcompany.com</p>
        //     </div>
        // </body>
        // </html>";

        $mail->Subject = " Queue Confirmation - Your Position: $position";
        $mail->Body = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; background-color: #f8f9fa; color: #333; padding: 20px; }
                .container { background: white; max-width: 600px; margin: 0 auto; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); }
                h2 { color: #28a745; text-align: center; }
                p { font-size: 16px; line-height: 1.5; }
                .queue-info { background: #f4f4f4; padding: 15px; border-radius: 5px; margin: 20px 0; }
                .footer { font-size: 12px; color: #777; text-align: center; margin-top: 20px; }
                .btn { background: #28a745; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: bold; display: inline-block; text-align: center; }
                .btn:hover { background: #218838; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h2>✅ Queue Confirmation</h2>
                <p>Dear <strong>$name</strong>,</p>
                <p>You have been successfully added to the queue. Below are your details:</p>

                <div class='queue-info'>
                    <p><strong>📌 Name:</strong> $name</p>
                    <p><strong>📞 Phone:</strong> $phone</p>
                    <p><strong>🔢 Queue Position:</strong> #$position</p>
                    <p><strong>⏳ Estimated Wait Time:</strong>" . ($position * 8) . " minutes</p>
                </div>

                <p>📢 We will notify you when it's your turn. In the meantime, you can check your status below:</p>
                <p style='text-align: center;'>
                    <a href='http://localhost/final_foss/queue_status.html?phone=$phone' class='btn'>🔍 Check Queue Status</a>
                </p>


                <hr>
                <p class='footer'>📍 <strong>Your Company Name</strong> | Contact: support@yourcompany.com</p>
            </div>
        </body>
        </html>";

        $mail->send();
        return true; // Success
    } catch (Exception $e) {
        error_log("Email error: " . $mail->ErrorInfo);
        echo json_encode(["error" => "Email sending failed: " . $mail->ErrorInfo]);
        return false; // Failure
    }
}
?>
