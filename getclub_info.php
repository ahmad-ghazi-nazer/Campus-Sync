
<!DOCTYPE html>
<html>
<head>
    <title>Campus Sync Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <style>
        body {
            font-family: Arial, sans-serif;
    .responsive-image {
        width: 50%; /* Makes the image take up half of the container's width */
        height: auto; /* Maintains the aspect ratio */
        display: block; /* Ensures it doesn't inline with other elements */
        margin: 0 auto; /* Centers the image horizontally */
    }
        }
    </style>
   <script>
document.addEventListener("DOMContentLoaded", function() {
    fetch('club_info.php')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('data-container');
            if(data.length > 0){
                const table = document.createElement('table');
                table.className = 'table-auto w-full text-white';
                const thead = document.createElement('thead');
                thead.className = 'text-xs text-left text-gray-400 uppercase bg-gray-700';
                const headerRow = document.createElement('tr');

                ['Club Name', 'Club Brief', 'President ID'].forEach((text, index) => {
                    const headerCell = document.createElement('th');
                    headerCell.textContent = text;
                    headerCell.className = 'px-4 py-2';
                    headerRow.appendChild(headerCell);
                });

                thead.appendChild(headerRow);
                table.appendChild(thead);

                const tbody = document.createElement('tbody');
                tbody.className = 'bg-gray-800';

                data.forEach(item => {
                    const row = document.createElement('tr');
                    Object.entries(item).forEach(([key, text], index) => {
                        const cell = document.createElement('td');
                        // Format the Club Brief text to break every 30 words
                        if (key === 'Club_Brief') {
                            cell.innerHTML = text.split(' ').reduce((acc, word, i) => {
                                return acc + ((i % 30 === 0 && i !== 0) ? '<br>' : ' ') + word;
                            }, '');
                            cell.className = 'border px-4 py-2 whitespace-pre-wrap'; // Use whitespace-pre-wrap to respect new lines
                        } else {
                            cell.textContent = text;
                            cell.className = 'border px-4 py-2';
                        }
                        row.appendChild(cell);
                    });
                    tbody.appendChild(row);
                });

                table.appendChild(tbody);
                container.appendChild(table);
            } else {
                container.textContent = "No clubs found";
            }
        })
        .catch(error => console.error('Error fetching data:', error));
});
</script>


</head>
<body class="bg-gray-800 text-white">
    <div class="flex flex-col h-screen">
        <!-- Header -->
        <div class="bg-black p-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <img alt="University logo" class="h-50" height="100" src="psut_logo.png" width="100"/>
                <div class="text-3xl font-bold">
                    CAMPUS SYNC | CLUB INFO
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
                    <a class="flex items-center space-x-2 text-white hover:text-blue-300" href="club_president_dashboard.html">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                    <a class="flex items-center space-x-2 text-white hover:text-blue-300" href="Apply_for_event.html">
                        <i class="fas fa-calendar-plus"></i>
                        <span>Apply for event</span>
                    </a>
                    <a class="flex items-center space-x-2 text-blue-500 hover:text-blue-300" href="club-info.html">
                        <i class="fas fa-info-circle"></i>
                        <span>Your Club Info</span>
                    </a>
                    <a class="flex items-center space-x-2 text-white hover:text-blue-300" href="Track_event_request_v.php">
                        <i class="fas fa-tasks"></i>
                        <span>Track Event Request</span>
                    </a>
                </div>
                <div class="text-gray-400">
                    Support
                </div>
                <a class="flex items-center space-x-2 text-white hover:text-blue-300" href="Helpandsupport_cp.html">
                    <i class="fas fa-question-circle"></i>
                    <span>Help & Support</span>
                </a>
            </div>
            <!-- Content Area -->
            <div class="flex-1 p-10 space-y-6">
            <img alt="Group of people posing for a photo at an event" class="rounded responsive-image" src="PSUT DASHBOAR.webp" style="max-width: 100%; height: auto;">
                
                <!-- Data Display Area -->
                <div id="data-container">
                    <!-- Club data will be inserted here -->
                </div>
            </div>
        </div>
    </div>
</body>
</html>
