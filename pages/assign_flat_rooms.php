<h3>Assign Rooms in Flats</h3>

<!-- Form to Assign Room -->
<form action="" method="post">
    <label for="student_id">Student:</label>
    <select name="student_id" required>
        <option value="">Select Student</option>
        <?php
        // Fetch students
        $students = $conn->query("SELECT student_id, first_name, last_name FROM students WHERE current_status = 'Waiting'");
        while ($row = $students->fetch_assoc()) {
            echo "<option value='{$row['student_id']}'>{$row['first_name']} {$row['last_name']}</option>";
        }
        ?>
    </select>
    <br>

    <label for="room_id">Room:</label>
    <select name="room_id" required>
        <option value="">Select Room</option>
        <?php
        // Fetch available rooms
        $rooms = $conn->query("SELECT fr.room_id, fr.room_number, sf.apartment_number 
                               FROM flat_rooms fr 
                               JOIN student_flats sf ON fr.flat_id = sf.flat_id
                               WHERE fr.room_id NOT IN (SELECT room_id FROM leases)");
        while ($row = $rooms->fetch_assoc()) {
            echo "<option value='{$row['room_id']}'>Room {$row['room_number']} (Flat {$row['apartment_number']})</option>";
        }
        ?>
    </select>
    <br>

    <label for="lease_start_date">Start Date:</label>
    <input type="date" name="lease_start_date" required>
    <br>

    <label for="duration_semesters">Duration (Semesters):</label>
    <input type="number" name="duration_semesters" required>
    <br>

    <button type="submit" name="assign_room_flat">Assign Room</button>
</form>

<hr>

<h4>Assigned Flat Rooms</h4>
<table border="1">
    <tr>
        <th>Lease ID</th>
        <th>Student Name</th>
        <th>Room Number</th>
        <th>Apartment Number</th>
        <th>Actions</th>
    </tr>
    <?php
    $assignments = $conn->query("
        SELECT l.lease_id, s.first_name, s.last_name, fr.room_number, sf.apartment_number
        FROM leases l
        JOIN students s ON l.student_id = s.student_id
        JOIN flat_rooms fr ON l.room_id = fr.room_id
        JOIN student_flats sf ON fr.flat_id = sf.flat_id
    ");
    if ($assignments->num_rows > 0) {
        while ($row = $assignments->fetch_assoc()) {
            echo "<tr>
                <td>{$row['lease_id']}</td>
                <td>{$row['first_name']} {$row['last_name']}</td>
                <td>Room {$row['room_number']}</td>
                <td>Flat {$row['apartment_number']}</td>
                <td>
                    <a href='room_assignments.php?type=flat&remove={$row['lease_id']}'>Remove</a>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No room assignments found.</td></tr>";
    }
    ?>
</table>

<?php
// Assign Room Logic for Flats
if (isset($_POST['assign_room_flat'])) {
    $student_id = $_POST['student_id'];
    $room_id = $_POST['room_id'];
    $lease_start_date = $_POST['lease_start_date'];
    $duration_semesters = intval($_POST['duration_semesters']);
    $lease_end_date = date('Y-m-d', strtotime("+$duration_semesters months", strtotime($lease_start_date)));

    $sql = "INSERT INTO leases (student_id, room_id, lease_start_date, lease_end_date, duration_semesters) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Query preparation failed: " . $conn->error);
    }

    $stmt->bind_param("iissi", $student_id, $room_id, $lease_start_date, $lease_end_date, $duration_semesters);

    if ($stmt->execute()) {
        $conn->query("UPDATE students SET current_status = 'Placed' WHERE student_id = $student_id");
        echo "<p>Room assigned successfully.</p>";
        header("Location: room_assignments.php?type=flat");
        exit();
    } else {
        echo "<p>Error assigning room: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

// Remove Room Assignment Logic for Flats
if (isset($_GET['remove'])) {
    $lease_id = intval($_GET['remove']);

    $result = $conn->query("SELECT student_id FROM leases WHERE lease_id = $lease_id");
    if ($result->num_rows > 0) {
        $lease = $result->fetch_assoc();
        $student_id = $lease['student_id'];

        $sql = "DELETE FROM leases WHERE lease_id = $lease_id";
        if ($conn->query($sql) === TRUE) {
            $conn->query("UPDATE students SET current_status = 'Waiting' WHERE student_id = $student_id");
            echo "<p>Room assignment removed successfully.</p>";
            header("Location: room_assignments.php?type=flat");
            exit();
        } else {
            echo "<p>Error removing assignment: " . $conn->error . "</p>";
        }
    }
}
?>
