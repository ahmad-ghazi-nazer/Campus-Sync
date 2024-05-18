<?php
// Remove session_start() here as it's already started in the main file

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$database = "compus_sync_db"; // Ensure this database name is correct

// Create connection
$conn = new mysqli($servername, $username, $password, $database); // corrected variable name here

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT suggested_name, suggested_location, suggested_date, club_name, event_details, current_stage, request_status, approver_id FROM event_request";
$result = $conn->query($sql);

if ($result === false) {
  // SQL Error handling
  die("SQL error: " . $conn->error);
}

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    // Check approver_id and replace with the corresponding title
    $approver_title = $row["approver_id"];
    switch ($approver_title) {
      case 555:
        $approver_title = "Officer of Clubs Office";
        break;
      case 222:
        $approver_title = "Dean of Student Affairs";
        break;
      case 333:
        $approver_title = "Administrative Manager";
        break;
      case 444:
        $approver_title = "Public Relations";
        break;
      case 111:
        $approver_title = "University President";
        break;
      default:
        $approver_title = htmlspecialchars($approver_title); // Keep the ID if no match found
    }

    echo "<tr class='bg-gray-700'>";
    echo "<td class='py-3 px-6 border'>" . htmlspecialchars($row["suggested_name"]) . "</td>";
    echo "<td class='py-3 px-6 border'>" . htmlspecialchars($row["suggested_location"]) . "</td>";
    echo "<td class='py-3 px-6 border'>" . htmlspecialchars($row["suggested_date"]) . "</td>";
    echo "<td class='py-3 px-6 border'>" . htmlspecialchars($row["club_name"]) . "</td>";
    echo "<td class='py-3 px-6 border wrap-text'>" . htmlspecialchars($row["event_details"]) . "</td>"; // Apply class here
    echo "<td class='py-3 px-6 border'>" . htmlspecialchars($row["current_stage"]) . "</td>";
    echo "<td class='py-3 px-6 border'>" . htmlspecialchars($row["request_status"]) . "</td>";
    echo "<td class='py-3 px-6 border'>" . htmlspecialchars($approver_title) . "</td>";
    echo "</tr>";
  }
} else {
  echo "<tr><td colspan='8' class='py-3 px-6'>No results found</td></tr>";
}
$conn->close();
?>
