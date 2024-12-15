

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leases</title>
</head>

<style>


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

    .ml-container{
        text-align: center;

    }

    .form-container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 15px; /* Even spacing between elements */
        width: 90%;
        max-width: 400px;
        margin: auto; /* Center horizontally */
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 10px;
        background-color: #f9f9fc;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
    }

    /* Style the title */
    .form-container h3 {
        margin: 0;
        color: #34495e;
        text-align: center;
    }

    /* Style the input, select, and textarea */
    .form-container input,
    .form-container select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }

    /* Style the button */
    .form-container button {
        width: 100%;
        padding: 10px;
        background-color: #f1485b;
        color: white;
        border: none;
        padding: 10px 20px;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    /* Button hover effect */
    .form-container button:hover {
        background-color: white;
        color: #f1485b;
        border: 1px solid #f1485b;
    }
    
</style>


<body>

<?php
session_start();
include '../includes/db_connect.php';
include '../includes/header.php';
?>

<h2 class="ml-container">Manage Leases</h2>

<!-- Lease Form -->
 <div class="form-container">
    <h3>Add Lease</h3>
    <form action="" method="post">
        <label for="student_id">Student:</label>
        <select name="student_id" required>
            <option value="">Select Student</option>
            <?php
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
            $rooms = $conn->query("SELECT room_id, room_number FROM hall_rooms WHERE room_id NOT IN (SELECT room_id FROM leases)");
            while ($row = $rooms->fetch_assoc()) {
                echo "<option value='{$row['room_id']}'>Room {$row['room_number']}</option>";
            }
            ?>
        </select>
        <br>

        <label for="lease_start_date">Start Date:</label>
        <input type="date" name="lease_start_date" required>
        <br>

        <label for="duration_semesters">Duration (Semesters):</label>
        <input type="number" name="duration_semesters" required>
        <br> <br>

        <button type="submit" name="add_lease">Add Lease</button>
    </form>   
</div>


<!-- Handle Lease Form Submission -->
<?php
if (isset($_POST['add_lease'])) {
    $student_id = $_POST['student_id'];
    $room_id = $_POST['room_id'];
    $lease_start_date = $_POST['lease_start_date'];
    $duration_semesters = intval($_POST['duration_semesters']);
    $lease_end_date = date('Y-m-d', strtotime("+$duration_semesters months", strtotime($lease_start_date)));

    // Fetch the monthly rent for the selected room
    $rent_query = "SELECT monthly_rent FROM hall_rooms WHERE room_id = ?";
    $rent_stmt = $conn->prepare($rent_query);
    $rent_stmt->bind_param("i", $room_id);
    $rent_stmt->execute();
    $rent_stmt->bind_result($monthly_rent);
    $rent_stmt->fetch();
    $rent_stmt->close();

    if ($monthly_rent === null) {
        echo "<p>Error: Unable to fetch rent amount for the selected room.</p>";
        exit();
    }

    // Calculate total rent amount for the lease duration
    $rent_amount = $monthly_rent * $duration_semesters;

    // Insert the lease into the database
    $sql = "INSERT INTO leases (student_id, room_id, lease_start_date, lease_end_date, duration_semesters, rent_amount) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("iissid", $student_id, $room_id, $lease_start_date, $lease_end_date, $duration_semesters, $rent_amount);
        if ($stmt->execute()) {
            $conn->query("UPDATE students SET current_status = 'Placed' WHERE student_id = $student_id");
            echo "<p>Lease added successfully.</p>";
            header("Location: leases.php");
            exit();
        } else {
            echo "<p>Error adding lease: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p>Error preparing query: " . $conn->error . "</p>";
    }
}
?>


<!-- Display Assigned Leases -->
<h3>Assigned Leases</h3>
<table border="1">
    <tr>
        <th>Lease ID</th>
        <th>Student Name</th>
        <th>Room Number</th>
        <th>Hall Name</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Duration (Semesters)</th>
        <th>Rent Amount</th>
        <th>Payment Status</th>
        <th>Actions</th>
    </tr>

    <?php
    $sql = "
        SELECT l.lease_id, s.first_name, s.last_name, hr.room_number, h.name AS hall_name,
               l.lease_start_date, l.lease_end_date, l.duration_semesters, l.rent_amount, l.payment_status
        FROM leases l
        JOIN students s ON l.student_id = s.student_id
        JOIN hall_rooms hr ON l.room_id = hr.room_id
        JOIN halls_of_residence h ON hr.hall_id = h.hall_id
    ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['lease_id']}</td>
                <td>{$row['first_name']} {$row['last_name']}</td>
                <td>{$row['room_number']}</td>
                <td>{$row['hall_name']}</td>
                <td>{$row['lease_start_date']}</td>
                <td>{$row['lease_end_date']}</td>
                <td>{$row['duration_semesters']}</td>
                <td>{$row['rent_amount']}</td>
                <td>{$row['payment_status']}</td>
                <td>
                    <a href='leases.php?edit={$row['lease_id']}'>Edit</a> | 
                    <a href='leases.php?delete={$row['lease_id']}' 
                       onclick=\"return confirm('Are you sure you want to delete this lease?');\">Delete</a>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='10'>No leases found.</td></tr>";
    }
    ?>
</table>

<?php include '../includes/footer.php'; ?>
</body>
</html>




