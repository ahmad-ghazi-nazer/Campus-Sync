<?php
session_start();
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
        .wrap-text {
            word-wrap: break-word; /* Legacy browsers */
            overflow-wrap: break-word; /* Preferred for modern browsers */
            white-space: normal; /* Ensures text doesn't stay on one line */
        }
        .responsive-image {
            width: 50%; /* Makes the image take up half of the container's width */
            height: auto; /* Maintains the aspect ratio */
            display: block; /* Ensures it doesn't inline with other elements */
            margin: 0 auto; /* Centers the image horizontally */
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
                    CAMPUS SYNC | Track Events Requests
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
                    <a class="flex items-center space-x-2 text-white hover:text-blue-30" href="club_president_dashboard.html">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                    <a class="flex items-center space-x-2 text-white hover:text-blue-300" href="Apply_for_event.html">
                        <i class="fas fa-calendar-plus"></i>
                        <span>Apply for event</span>
                    </a>
                    <a class="flex items-center space-x-2 text-white hover:text-blue-300" href="getclub_info.php">
                        <i class="fas fa-info-circle"></i>
                        <span>Your Club Info</span>
                    </a>
                    <a class="flex items-center space-x-2 text-blue-500 hover:text-blue-300" href="track_Event_approval.html">
                        <i class="fas fa-tasks"></i>
                        <span>Track Event Request</span>
                    </a>
                </div>
                <div class="text-gray-400">
                    Support
                </div>
                <a class="flex items-center space-x-2 text-white hover:text-blue-300" href="Helpandsupport_cp.html">
                    <i class="fas fa-question-circle"></i>
                    <span>Help &amp; Support</span>
                </a>
            </div>
            <!-- Content Area -->
            <div class="flex-1 p-10 space-y-6">
                <img alt="Group of people posing for a photo at an event" class="rounded responsive-image" src="PSUT DASHBOAR.webp" style="max-width: 100%; height: auto;">
                <div class="overflow-x-auto mt-4">
                    <table class="w-full text-base text-left text-white">
                        <thead class="text-lg text-white uppercase bg-gray-700">
                            <tr>
                                <th class="py-3 px-6">Event Name</th>
                                <th class="py-3 px-6">Event Location</th>
                                <th class="py-3 px-6">Event Date</th>
                                <th class="py-3 px-6">Club Name</th>
                                <th class="py-3 px-6">Event Details</th>
                                <th class="py-3 px-6">Current Stage</th>
                                <th class="py-3 px-6">Request Status</th>
                                <th class="py-3 px-6">Approver Name</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800">
                            <?php include 'track_event_request.php'; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
