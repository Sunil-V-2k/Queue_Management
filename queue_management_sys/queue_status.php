<?php
require 'db_config.php'; // Ensure database connection

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (!isset($_GET['phone'])) {
        echo json_encode(["error" => "Phone number required"]);
        exit;
    }

    $phone = htmlspecialchars($_GET['phone']);

    // Find the user's queue position
    $stmt = $pdo->prepare("SELECT id, name, phone, email, 
        (SELECT COUNT(*) FROM queue q2 WHERE q2.id < q1.id) + 1 AS position
        FROM queue q1 WHERE phone = ? LIMIT 1");
    $stmt->execute([$phone]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$customer) {
        echo json_encode(["error" => "Customer not found in queue"]);
    } else {
        $customer['estimated_time'] = $customer['position'] * 8; // 8 minutes per customer
        echo json_encode($customer);
    }
}
?>
