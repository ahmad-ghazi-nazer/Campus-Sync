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

function submitClubCreationRequest($userId, $clubName, $description, $clubstatus, $currentStage, $approver, $conn) {
    $stmt = $conn->prepare("INSERT INTO club_request (applicant_id, club_name, club_description, request_status, current_stage, approver_id) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("isssii", $userId, $clubName, $description, $clubstatus, $currentStage, $approver);
    if ($stmt->execute()) {
        return true; 
    } else {
        return false;
    }
}

function updateApplicantRole($userId, $conn) {
    $stmt = $conn->prepare("UPDATE user_role SET role_name = 'Pending Club President' WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['user_id'])) {
        $applicant_id = $_SESSION['user_id'];
        $clubName = $_POST['name'];
        $description = $_POST['brief'];
        $currentStage = 1;

        if (submitClubCreationRequest($applicant_id, $clubName, $description, 'pending', $currentStage, 555, $conn)) {
            if (updateApplicantRole($applicant_id, $conn)) {
                header('Location: club_created_sucsess.html'); // Redirect to the success page
                exit(); // It's important to call exit() after header() to make sure no further script execution can affect the redirection
            } else {
                echo "Club creation request submitted successfully but failed to update role.";
            }
        } else {
            echo "Failed to submit club creation request";
        }
        } else {
            echo "User not logged in";
        }
        } else {
            echo "Invalid request method";
        }
        

$conn->close();
?>
