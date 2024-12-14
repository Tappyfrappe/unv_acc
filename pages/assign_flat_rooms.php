
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<style>
    .arf-container{
        text-align: center;
        color: black;
    }

    .c-container {
    width: 100%;
    max-width: 400px;
    margin: 20px auto;
    padding: 20px;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    font-family: Arial, sans-serif;
}

.c-container h4 {
    margin-bottom: 15px;
    font-size: 18px;
    text-align: center;
    color: #333;
}

.c-container select {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: #fff;
    font-size: 14px;
    color: #333;
}

.c-container button {
    width: 100%;
    padding: 10px;
    background-color: #f1485b;
    color: #fff;
    border: none;
    border-radius: 4px;
    font-size: 14px;
    cursor: pointer;
    text-align: center;
    transition: background-color 0.3s;
}

.c-container button:hover {
    background-color: #45a049;
}

    table {
        margin: 0 auto; 
        border-collapse: collapse; 
    }

    table {
        width: 80%;
        margin: 20px auto; 
        border-collapse: collapse;
        background-color: white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
    }

    th, td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #34495e;
        color: white;
    }

    tr:hover {
        background-color: #f1f1f1;
    }



</style>



<body>
    <h3 class="arf-container">Assign Rooms in Flats</h3>

<!-- Form to Assign Room -->
 <div class="c-container">
    <form action="" method="post">
    <h4>Assign Room</h4>
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
        $rooms = $conn->query("SELECT room_id, room_number FROM flat_rooms WHERE room_id NOT IN (SELECT room_id FROM leases)");
        if (!$rooms) {
            die("Query Error: " . $conn->error);
        }
        while ($row = $rooms->fetch_assoc()) {
            echo "<option value='{$row['room_id']}'>Room {$row['room_number']}</option>";
        }
        ?>
    </select>
    <button type="submit" name="assign_room">Assign Room</button>
    </form>
 </div>


<hr>

<!-- Display Assigned Rooms -->
<h4>Assigned Rooms</h4>
<table border="1">
    <tr>
        <th>Student Name</th>
        <th>Room Number</th>
        <th>Actions</th>
    </tr>
    <?php
    $assignments = $conn->query("
        SELECT leases.lease_id, students.first_name, students.last_name, flat_rooms.room_number
        FROM leases
        JOIN students ON leases.student_id = students.student_id
        JOIN flat_rooms ON leases.room_id = flat_rooms.room_id
    ");
    if ($assignments->num_rows > 0) {
        while ($row = $assignments->fetch_assoc()) {
            echo "<tr>
                <td>{$row['first_name']} {$row['last_name']}</td>
                <td>Room {$row['room_number']}</td>
                <td>
                    <a href='?type=flat&remove={$row['lease_id']}'>Remove</a>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='3'>No room assignments found</td></tr>";
    }
    ?>
</table>

<?php
// Assign Room Logic
if (isset($_POST['assign_room'])) {
    $student_id = $_POST['student_id'];
    $room_id = $_POST['room_id'];

    $sql = "INSERT INTO leases (student_id, room_id, lease_start_date, duration_semesters)
            VALUES ($student_id, $room_id, CURDATE(), 1)";

    if ($conn->query($sql) === TRUE) {
        $conn->query("UPDATE students SET current_status = 'Placed' WHERE student_id = $student_id");
        echo "Room assigned successfully.";
        header("Location: room_assignments.php?type=flat");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Remove Room Assignment Logic
if (isset($_GET['remove'])) {
    $lease_id = $_GET['remove'];

    $sql = "DELETE FROM leases WHERE lease_id = $lease_id";

    if ($conn->query($sql) === TRUE) {
        echo "Room assignment removed successfully.";
        header("Location: room_assignments.php?type=flat");
        exit();
    } else {
        echo "Error removing assignment: " . $conn->error;
    }
}
?>

</body>
</html>

