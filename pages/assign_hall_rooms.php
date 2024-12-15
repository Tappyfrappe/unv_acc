
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<style>
    .arh-container, .ar-container {
        color: black;
        text-align: center;
    }

    .another-container {
        display: flex;
        flex-direction: column;
        gap: 15px;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        width: 300px;
        margin: 50px auto;
        background-color: #f9f9f9;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
    }

    .another-container select {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 1em;
    }

    .another-container button {
        padding: 10px;
        font-size: 1em;
        color: #fff;
        background-color:  #f1485b;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .another-container button:hover {
        background-color:  #f1485b;
    }
</style>


<body>
    <h3 class="arh-container">Assign Rooms in Halls</h3>

<!-- Form to Assign Room -->

<form action="" method="post">
    
    <div class="another-container">
        <h4 class="ar-container">Assign Room</h4>
        <select name="student_id" required>
        <option value="">Select Student</option>
        <?php
        $students = $conn->query("SELECT student_id, first_name, last_name FROM students WHERE current_status = 'Waiting'");
        while ($row = $students->fetch_assoc()) {
            echo "<option value='{$row['student_id']}'>{$row['first_name']} {$row['last_name']}</option>";
        }
        ?>
        </select>
        <select name="room_id" required>
        <option value="">Select Room</option>
        <?php
        $rooms = $conn->query("
            SELECT hall_rooms.room_id, hall_rooms.room_number, halls_of_residence.name AS hall_name 
            FROM hall_rooms
            JOIN halls_of_residence ON hall_rooms.hall_id = halls_of_residence.hall_id
            WHERE hall_rooms.room_id NOT IN (SELECT room_id FROM leases)
        ");
        if ($rooms->num_rows > 0) {
            while ($row = $rooms->fetch_assoc()) {
            echo "<option value='{$row['room_id']}'>Room {$row['room_number']} ({$row['hall_name']})</option>";
            }
        } else {
            echo "<option value=''>No available rooms</option>";
        }
        ?>
        </select>
        <button type="submit" name="assign_room">Assign Room</button>
    </div>
    
</form>

<hr>

<?php
// Assign Room Logic
if (isset($_POST['assign_room'])) {
    $student_id = $_POST['student_id'];
    $room_id = $_POST['room_id'];

    $stmt = $conn->prepare("INSERT INTO leases (student_id, room_id, lease_start_date, duration_semesters) VALUES (?, ?, CURDATE(), 1)");
    $stmt->bind_param("ii", $student_id, $room_id);

    if ($stmt->execute()) {
        $conn->query("UPDATE students SET current_status = 'Placed' WHERE student_id = $student_id");
        $_SESSION['message'] = "Room assigned successfully!";
        header("Location: room_assignments.php?type=hall");
        exit();
    } else {
        $_SESSION['error'] = "Error assigning room.";
    }
}


// Remove Room Assignment Logic
if (isset($_GET['remove'])) {
    $lease_id = $_GET['remove'];

    $sql = "DELETE FROM leases WHERE lease_id = $lease_id";

    if ($conn->query($sql) === TRUE) {
        echo "Room assignment removed successfully.";
        header("Location: room_assignments.php?type=hall");
        exit();
    } else {
        echo "Error removing assignment: " . $conn->error;
    }
}
?>


<script>
    function fetchRooms(hallId) {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetch_rooms.php?hall_id=' + hallId, true);
        xhr.onload = function () {
            if (this.status === 200) {
                document.getElementById('room_dropdown').innerHTML = this.responseText;
            }
        };
        xhr.send();
    }
</script>

</body>
</html>




