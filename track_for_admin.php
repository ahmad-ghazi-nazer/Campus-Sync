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

// SQL query to select data
$sql = "SELECT applicant_id, club_name, club_description, request_status, current_stage, approver_id FROM club_request";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Sync Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .responsive-image {
            width: 50%;
            height: auto;
            display: block;
            margin: 0 auto;
        }
    </style>
</head>
<body class="bg-gray-800 text-white">
    <div class="flex flex-col h-screen">
        <!-- Header -->
        <div class="bg-black p-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <img alt="University logo" class="h-50" height="100" src="psut_logo.png" width="100"/>
                <div class="text-3xl font-bold">
                    CAMPUS SYNC | VIEW & TRACK CLUB REQUEST
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <button class="bg-red-600 px-4 py-2 rounded text-white" onclick="window.location.href='login.html';">
                    Log out!
                </button>
            </div>
        </div>
        <!-- Main Content -->
        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar -->
            <div class="w-64 bg-gray-900 p-5 space-y-6">
                <div class="text-gray-400">
                    Menu
                </div>
                <div class="space-y-2">
                    <a class="flex items-center space-x-2 text-White-500 hover:text-blue-300" href="Dashboard.php">
                        <i class="fas fa-calendar-plus"></i>
                        <span>
DashBoard                        </span>
                    </a>
                </div>
                <div class="space-y-2">
                    <a class="flex items-center space-x-2 text-blue-500 hover:text-blue-300" href="track_club_request.php">
                        <i class="fas fa-calendar-plus"></i>
                        <span>
                            VIEW &  TRACK CLUB REQUEST!
                        </span>
                    </a>
                </div>
               
            </div>
            <!-- Content Area -->
            <div class="flex-1 p-10 space-y-6">
                <img alt="Group of people posing for a photo at an event" class="rounded responsive-image" src="PSUT DASHBOAR.webp" style="max-width: 100%; height: auto;">
                <div class="text-lg">
                    <table class="table-auto w-full">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">Applicant ID</th>
                                <th class="px-4 py-2">Club Name</th>
                                <th class="px-4 py-2">Club Description</th>
                                <th class="px-4 py-2">Request Status</th>
                                <th class="px-4 py-2">Current Stage</th>
                                <th class="px-4 py-2">Approver ID</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                // Output data of each row
                                while($row = $result->fetch_assoc()) {
                                    // Check approver_id and replace with the corresponding title
                                    $approver_title = $row["approver_id"];
                                    switch ($approver_title) {
                                        case 555:
                                            $approver_title = "Officer of Clubs Office";
                                            break;
                                        case 222:
                                            $approver_title = "Dean of Students Affairs";
                                            break;
                                        case 111:
                                            $approver_title = "University President";
                                            break;
                                    }

                                    echo '<tr class="bg-gray-700">
                                            <td class="border px-4 py-2">' . htmlspecialchars($row["applicant_id"]) . '</td>
                                            <td class="border px-4 py-2">' . htmlspecialchars($row["club_name"]) . '</td>
                                            <td class="border px-4 py-2">' . htmlspecialchars($row["club_description"]) . '</td>
                                            <td class="border px-4 py-2">' . htmlspecialchars($row["request_status"]) . '</td>
                                            <td class="border px-4 py-2">' . htmlspecialchars($row["current_stage"]) . '</td>
                                            <td class="border px-4 py-2">' . htmlspecialchars($approver_title) . '</td>
                                          </tr>';
                                }
                            } else {
                                echo '<tr><td colspan="6" class="px-4 py-2">No records found</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php
    $conn->close();
    ?>
</body>
</html>
