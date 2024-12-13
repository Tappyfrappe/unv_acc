<?php
include 'db_connection.php'; // Include your DB connection script

if (isset($_GET['hall_id'])) {
    $hall_id = intval($_GET['hall_id']); // Sanitize input

    $sql = "SELECT room_id, room_number 
            FROM hall_rooms 
            WHERE hall_id = $hall_id 
            AND room_id NOT IN (SELECT room_id FROM leases)";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<option value='{$row['room_id']}'>Room {$row['room_number']}</option>";
        }
    } else {
        echo "<option value=''>No available rooms</option>";
    }
}
?>
