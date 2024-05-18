<?php
header('Content-Type: application/json');

// Database configuration and connection setup
$servername = "localhost";
$username = "root";
$password = "";
$database = "compus_sync_db";
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query
$sql = "SELECT Club_Name, Club_Brief, President_ID FROM clubs";
$result = $conn->query($sql);

$clubs = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $clubs[] = $row;
    }
    echo json_encode($clubs);
} else {
    echo json_encode([]);
}

$conn->close();
?>
