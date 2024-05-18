<?php

session_start(); // Start the session if it's not already started

$servername = "localhost"; 
$username = "root"; 
$password = "";
$database = "compus_sync_db"; 

// Enable error reporting for mysqli
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function submitEventRequest($suggested_name, $suggested_location, $suggested_date, $club_name, $event_details, $current_stage, $request_status, $approver_id, $conn) {
    $stmt = $conn->prepare("INSERT INTO event_request (suggested_name, suggested_location, suggested_date, club_name, event_details, current_stage, request_status, approver_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("sssssssi", $suggested_name, $suggested_location, $suggested_date, $club_name, $event_details, $current_stage, $request_status, $approver_id);
    if ($stmt->execute()) {
        return true; 
    } else {
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $suggested_name = $_POST['suggested_name'];
    $suggested_location = $_POST['suggested_location'];
    $suggested_date = $_POST['suggested_date']; // Assuming this is the name of the datetime input field in your form
    $club_name = $_POST['club_name'];
    $event_details = $_POST['event_details'];
    $current_stage = 1; 
    $request_status = 'Pending'; 
    $approver_id = 555; 

    if (submitEventRequest($suggested_name, $suggested_location, $suggested_date, $club_name, $event_details, $current_stage, $request_status, $approver_id, $conn)) {
        // Redirect to the success page
        header('Location: event_created_sucess.html');
        exit; // Ensure no further script execution in case of redirection
     } else {
        echo "Failed to submit event request";
    }
} else {
    echo "Invalid request method";
}

$conn->close();
?>