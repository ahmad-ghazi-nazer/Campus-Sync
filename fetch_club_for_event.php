<?php
header('Content-Type: application/json');

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

// SQL to fetch clubs
$sql = "SELECT Club_Name FROM clubs";
$result = $conn->query($sql);

$clubs = [];

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $clubs[] = $row['Club_Name'];
    }
} else {
    echo "0 results";
}

echo json_encode($clubs);

$conn->close();
?>
