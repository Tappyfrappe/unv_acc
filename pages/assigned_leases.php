<?php
session_start();
include '../includes/db_connect.php';
include '../includes/header.php';
?>

<h2>Assigned Leases</h2>

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
    // Fetch assigned leases
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
        echo "<tr><td colspan='10'>No assigned leases found.</td></tr>";
    }
    ?>
</table>

<?php include '../includes/footer.php'; ?>
