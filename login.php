<?php

$servername = "localhost";
$dbUsername = "root";
$password = "";
$dbname = "compus_sync_db";

// Create connection
$conn = new mysqli($servername, $dbUsername, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $password = $_POST['passkey'];  // This should match the 'name' attribute of your password input in the HTML form

    // Prepare a select statement to get user information
    $query_user = $conn->prepare("SELECT user_id, passkey FROM user WHERE user_id = ?");
    $query_user->bind_param("s", $user_id);

    if ($query_user->execute()) {
        $result_user = $query_user->get_result();

        if ($result_user->num_rows === 1) {
            $user = $result_user->fetch_assoc();
        
            // Use password_verify to check the entered password against the hashed password from the database
            if (password_verify($password, $user['passkey'])) {
                // After successful verification, store the user ID in the session
                $_SESSION['user_id'] = $user_id;
        
                // Prepare a select statement to get user role
                $query_role = $conn->prepare("SELECT role_name FROM user_role WHERE user_id = ?");
                $query_role->bind_param("s", $user_id);
                $query_role->execute();
                $result_role = $query_role->get_result();
                
                if ($result_role->num_rows === 1) {
                    $role = $result_role->fetch_assoc();
                
                    $_SESSION['loggedin'] = true;
                    $_SESSION['user_id'] = $user['user_id'];
                
                    // Check the user's role
                    if ($role['role_name'] === "New Club President") {
                        header("Location: Apply_for_new_club.html");
                        exit;
                    } elseif ($role['role_name'] === "Active Club President") {
                        // Redirecting to the club president's dashboard
                        header("Location: club_president_dashboard.html");
                        exit;
                    } elseif ($role['role_name'] === "Pending Club President") {
                        // Redirecting to the specified external URL
                        header("Location: club_created_sucsess.html");
                        exit;
                    } elseif ($role['role_name'] === "University_President" || 
                              $role['role_name'] === "Dean_of_Students_Affairs" || 
                              $role['role_name'] === "ADMINTRATIVE_MANGER" || 
                              $role['role_name'] === "HEAD_OF_PR" || 
                              $role['role_name'] === "Clubs_Officer") {
                
                        header("Location: Dashboard.php");
                        exit;
                    } else {
                        header("Location: login.html");
                        exit;
                    }
                
                } else {
                    echo "User role not found.";
                }
                $query_role->close();
            } else {
                echo "Password is incorrect.";
            }
        } else {
            echo "USER ID not found.";
        }
        $query_user->close();
    } else {
        echo "Error executing query: " . $query_user->error;
    }
}

$conn->close();

?>
