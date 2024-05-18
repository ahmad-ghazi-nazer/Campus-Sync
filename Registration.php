<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "compus_sync_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $user_id = intval($_POST['user_id']); // Constraint: Only accept integer values for user_id
    $username = preg_replace("/[^a-zA-Z]/", "", $_POST['username']); // Constraint: Only accept alphabet values for username
    $password = $_POST['passkey'];
    $email = $_POST['email'];
    $role = $_POST['role']; // Get the role from the POST data

    // Validate email format
    if (!preg_match('/^.*@(std\.)?psut\.edu\.jo$/', $email)) {
        echo "Invalid email format.";
        exit;
    }

    // Hash the password before storing it in the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Begin transaction to ensure data integrity
    $conn->begin_transaction();

    // Prepare an insert query with parameters for user_id, username, passkey, email
    $insert_user_query = $conn->prepare("INSERT INTO user (user_id, username, passkey, email) VALUES (?, ?, ?, ?)");
    $insert_user_query->bind_param("ssss", $user_id, $username, $hashed_password, $email);

    // Execute the prepared statement and check for success
    if ($insert_user_query->execute()) {
        // Prepare an insert query for user_role
        $insert_role_query = $conn->prepare("INSERT INTO user_role (user_id, role_name) VALUES (?, ?)");
        $insert_role_query->bind_param("ss", $user_id, $role);

        // Execute the prepared statement for user_role and check for success
        if ($insert_role_query->execute()) {
            header("Location: login.html");
            
                        $conn->commit();  // Commit the transaction
        } else {
            echo "Error in role insertion: " . $insert_role_query->error;
            $conn->rollback();  // Rollback the transaction on error
        }
    } else {
        echo "Error in user insertion: " . $insert_user_query->error;
        $conn->rollback();  // Rollback the transaction on error
    }

    // Close the prepared statements
    $insert_user_query->close();
    $insert_role_query->close();
}

// Close database connection
$conn->close();
?>
