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

// Retrieve the role name of the logged-in user
function getUserRoleName($userId) {
    global $conn;
    $stmt = $conn->prepare("SELECT role_name FROM user_role WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return $row['role_name'];
    }
    return null;
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Access denied. Please log in again.']);
    exit;
}

$userId = $_SESSION['user_id'];
$userRoleName = getUserRoleName($userId);

// Map roles to their corresponding approval stages
$approvals = [
    'Clubs_Officer' => 1,
    'Dean_of_Students_Affairs' => 2,
    'University_President' => 3
];

$currentStage = $approvals[$userRoleName] ?? null;

// Assign approver IDs based on stage
$approverIds = [
    1 => 555, // Officer of Clubs Office
    2 => 222, // Dean of Student Affairs
    3 => 111  // University President
];

// Fetch pending club requests for the current stage and role
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($currentStage) {
        $stmt = $conn->prepare("SELECT * FROM club_request WHERE request_status = 'pending' AND current_stage = ?");
        $stmt->bind_param("i", $currentStage);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $requests = [];
            while ($row = $result->fetch_assoc()) {
                $requests[] = $row;
            }
            echo json_encode($requests);
        } else {
            echo json_encode(['message' => 'No pending requests for the current stage']);
        }
    } else {
        echo json_encode(['error' => 'No permissions or invalid role.']);
        exit;
    }
}

// Handle the approval or rejection of a request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['club_name'], $_POST['action'])) {
    $club_name = $_POST['club_name'];
    $action = $_POST['action'];  // 'approve' or 'reject'
    if ($action === 'approve' && $currentStage) {
        if ($currentStage < 3) {
            // Move to the next stage
            $nextStage = $currentStage + 1;
            $nextApproverId = $approverIds[$nextStage];
            $stmt = $conn->prepare("UPDATE club_request SET current_stage = ?, approver_id = ? WHERE club_name = ?");
            $stmt->bind_param("iis", $nextStage, $nextApproverId, $club_name);
            if ($stmt->execute()) {
                // Update the role of the applicant to 'Pending Club President'
                $stmt = $conn->prepare("UPDATE user_role SET role_name = 'Pending Club President' WHERE user_id = (SELECT applicant_id FROM club_request WHERE club_name = ?)");
                $stmt->bind_param("s", $club_name);
                if ($stmt->execute()) {
                    echo json_encode(['message' => 'Request approved and moved to the next stage. Applicant role updated to Pending Club President']);
                } else {
                    echo json_encode(['error' => 'Failed to update role to Pending Club President']);
                }
            } else {
                echo json_encode(['error' => 'Failed to update the request']);
            }
        } else {
            // Final approval
            $stmt = $conn->prepare("UPDATE club_request SET request_status = 'approved', approver_id = ? WHERE club_name = ?");
            $stmt->bind_param("is", $approverIds[$currentStage], $club_name);
            if ($stmt->execute()) {
                // Insert into clubs table
                $stmt = $conn->prepare("INSERT INTO clubs (Club_Name, Club_Brief, President_ID) SELECT club_name, club_description, applicant_id FROM club_request WHERE club_name = ?");
                $stmt->bind_param("s", $club_name);
                if ($stmt->execute()) {
                    // Update the role of the applicant to 'Active Club President'
                    $stmt = $conn->prepare("UPDATE user_role SET role_name = 'Active Club President' WHERE user_id = (SELECT applicant_id FROM club_request WHERE club_name = ?)");
                    $stmt->bind_param("s", $club_name);
                    if ($stmt->execute()) {
                        echo json_encode(['message' => 'Request approved, club created, and role updated to Active Club President']);
                    } else {
                        echo json_encode(['error' => 'Failed to update role to Active Club President']);
                    }
                } else {
                    echo json_encode(['error' => 'Failed to create club']);
                }
            } else {
                echo json_encode(['error' => 'Failed to update the request']);
            }
        }       
    } elseif ($action === 'reject') {
        // Reject the request
        $stmt = $conn->prepare("UPDATE club_request SET request_status = 'rejected', approver_id = ? WHERE club_name = ?");
        $stmt->bind_param("is", $approverIds[$currentStage], $club_name);
        if ($stmt->execute()) {
            echo json_encode(['message' => 'Request rejected']);
        } else {
            echo json_encode(['error' => 'Failed to update the request']);
        }
    }
    
}

// Close database connection
$conn->close();
?>
