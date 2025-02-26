<?php
$host = 'localhost';
$dbname = 'queue_db';
$username = 'root';
$password = '';

// Connect to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    while (true) { // Keep running the listener
        // Fetch pending emails
        $stmt = $pdo->query("SELECT id, email FROM pending_emails WHERE sent_status = 0 LIMIT 1");
        $emailData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($emailData) {
            $email = $emailData['email'];
            $id = $emailData['id'];

            // Invoke send_mail.php with the email
            $output = shell_exec("php send_mail.php $email");

            // Update the email status to sent
            $update = $pdo->prepare("UPDATE pending_emails SET sent_status = 1 WHERE id = ?");
            $update->execute([$id]);

            echo "Email sent to: $email\n";
        }

        sleep(5); // Wait before checking again (Avoid overloading the database)
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
