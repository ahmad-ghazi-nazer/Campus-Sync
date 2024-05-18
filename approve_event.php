<?php
session_start();

header('Content-Type: application/json');
$servername = "localhost";
$username = "root";
$password = "";
$database = "compus_sync_db"; // Make sure this matches your actual database name

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => "Connection failed: " . $conn->connect_error]);
    exit;
}

function getUserRoleName($userId) {
    global $conn;
    $stmt = $conn->prepare("SELECT role_name FROM user_role WHERE user_id = ?");
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        return null;
    }
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return $row['role_name'];
    } else {
        error_log("No role found or fetch_assoc failed.");
        return null;
    }
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Access denied. Please log in again.']);
    exit;
}

$userId = $_SESSION['user_id'];
$userRoleName = getUserRoleName($userId);

$approvals = [
    'Clubs_Officer' => 1,
    'Dean_of_Students_Affairs' => 2,
    'ADMINTRATIVE_MANGER' => 3,
    'HEAD_OF_PR' => 4,
    'University_President' => 5
];

$currentStage = $approvals[$userRoleName] ?? null;
$approverIds = [1 => 555, 2 => 222, 3 => 333, 4 => 444, 5 => 111];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($currentStage) {
        $stmt = $conn->prepare("SELECT * FROM event_request WHERE request_status = 'pending' AND current_stage = ?");
        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error);
            echo json_encode(['error' => "Prepare failed: " . $conn->error]);
            exit;
        }
        $stmt->bind_param("i", $currentStage);
        $stmt->execute();
        $events = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        echo json_encode($events);
    } else {
        http_response_code(403);
        echo json_encode(['error' => 'No permissions or invalid role.']);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['suggested_name'], $_POST['action'])) {
    $suggestedName = $_POST['suggested_name'];
    $action = $_POST['action'];

    if ($action === 'approve' && $currentStage) {
        if ($currentStage < 5) {
            $nextStage = $currentStage + 1;
            $nextApproverId = $approverIds[$nextStage];
            $stmt = $conn->prepare("UPDATE event_request SET current_stage = ?, approver_id = ? WHERE suggested_name = ?");
            if (!$stmt) {
                error_log("Prepare failed: " . $conn->error);
                echo json_encode(['error' => "Prepare failed: " . $conn->error]);
                exit;
            }
            $stmt->bind_param("iis", $nextStage, $nextApproverId, $suggestedName);
        } else {
            $stmt = $conn->prepare("UPDATE event_request SET request_status = 'approved', approver_id = ? WHERE suggested_name = ?");
            if (!$stmt) {
                error_log("Prepare failed: " . $conn->error);
                echo json_encode(['error' => "Prepare failed: " . $conn->error]);
                exit;
            }
            $stmt->bind_param("is", $approverIds[$currentStage], $suggestedName);
        }
    } elseif ($action === 'deny') {
        $stmt = $conn->prepare("UPDATE event_request SET request_status = 'denied', approver_id = ? WHERE suggested_name = ?");
        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error);
            echo json_encode(['error' => "Prepare failed: " . $conn->error]);
            exit;   
        }
        $stmt->bind_param("is", $approverIds[$currentStage], $suggestedName);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action']);
        exit;
    }

    if (!$stmt->execute()) {
        echo json_encode(['error' => "SQL execution error: " . $stmt->error]);
    } else {
        echo json_encode(['message' => 'Thank you, your choice to : ' . ucfirst($action)]);
    }
    
}

$conn->close();
?>
