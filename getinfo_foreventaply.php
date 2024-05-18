<?php
session_start();

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$database = "compus_sync_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Access denied. Please log in again.']);
    exit;
}

$userId = $_SESSION['user_id'];

// Assuming we have a function or method to get the user's role and their allowed stage
// This needs to be implemented based on your application's logic
$currentStage = getUserCurrentStage($userId);  // Function to be implemented

if ($currentStage) {
    $stmt = $conn->prepare("SELECT event_id, suggested_name, suggested_location, suggested_date, club_name, event_details FROM event_request WHERE request_status = 'pending' AND current_stage = ?");
    $stmt->bind_param("i", $currentStage);
    $stmt->execute();
    $result = $stmt->fetch_all(MYSQLI_ASSOC);
    echo json_encode($result);
} else {
    echo json_encode(['error' => 'No permissions or invalid role.']);
}

// Close database connection
$conn->close();
?>
