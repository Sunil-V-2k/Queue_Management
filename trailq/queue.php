<?php
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'db.php';


// For estimated wait time
// require '../config.php';
// header("Content-Type: application/json");

// // Fetch Queue Data
// if ($_SERVER["REQUEST_METHOD"] === "GET") {
//     $query = "SELECT id, name, phone, timestamp FROM queue ORDER BY timestamp ASC";
//     $result = $conn->query($query);
//     $queue = [];

//     // Get average service time (Default: 5 min)
//     $avgServiceTime = 5;
//     $position = 1;

//     while ($row = $result->fetch_assoc()) {
//         $row["estimated_wait_time"] = $position * $avgServiceTime; // Calculate estimated wait time
//         $queue[] = $row;
//         $position++;
//     }

//     echo json_encode($queue);
//     exit;
// }
//











// Start output buffering to prevent unwanted HTML output
ob_start();

// Log request for debugging
$request_method = $_SERVER["REQUEST_METHOD"];
file_put_contents("log.txt", "Received: " . $request_method . " request\n", FILE_APPEND);

// Check if the database connection is working
if (!$conn) {
    ob_end_clean();
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

// Handle GET request (Fetch queue)
if ($request_method === "GET") {
    $query = "SELECT id, name, phone, timestamp FROM queue ORDER BY timestamp ASC";
    $result = $conn->query($query);
    $queue = [];

    while ($row = $result->fetch_assoc()) {
        $queue[] = $row;
    }

    ob_end_clean();
    echo json_encode($queue);
    exit;
}

// Handle POST request (Add customer)
if ($request_method === "POST") {
    $input = file_get_contents("php://input");
    file_put_contents("log.txt", "POST Data: " . $input . "\n", FILE_APPEND);

    $data = json_decode($input, true);

    // Check for JSON errors
    if (json_last_error() !== JSON_ERROR_NONE) {
        ob_end_clean();
        echo json_encode(["error" => "Invalid JSON format"]);
        exit;
    }

    if (!isset($data["name"]) || !isset($data["phone"])) {
        ob_end_clean();
        echo json_encode(["error" => "Missing name or phone"]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO queue (name, phone, timestamp) VALUES (?, ?, NOW())");
    $stmt->bind_param("ss", $data["name"], $data["phone"]);

    if ($stmt->execute()) {
        ob_end_clean();
        echo json_encode(["message" => "Customer added successfully"]);
    } else {
        ob_end_clean();
        echo json_encode(["error" => "Database error"]);
    }

    $stmt->close();
    exit;
}

// Handle DELETE request (Serve next customer)
if ($request_method === "DELETE") {
    $stmt = $conn->prepare("DELETE FROM queue ORDER BY timestamp ASC LIMIT 1");

    if ($stmt->execute()) {
        ob_end_clean();
        echo json_encode(["message" => "Customer served successfully"]);
    } else {
        ob_end_clean();
        echo json_encode(["error" => "Database error"]);
    }

    $stmt->close();
    exit;
}

// If the request method is not supported
ob_end_clean();
echo json_encode(["error" => "Invalid request method"]);
exit;
?>




<!-- CREATE TABLE queue (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
); -->
