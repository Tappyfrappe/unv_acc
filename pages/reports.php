<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<?php

include '../includes/db_connect.php';
include '../includes/header.php';
?>

<h2>Reports</h2>

<!-- Navigation for Reports -->
<nav>
    <ul>
        <li><a href="?report=waiting_list">Students on Waiting List</a></li>
        <li><a href="?report=available_rooms">Available Rooms</a></li>
        <li><a href="?report=unpaid_invoices">Unpaid Invoices</a></li>
        <li><a href="?report=rent_summary">Rent Summary</a></li>
    </ul>
</nav>

<hr>

<?php
// Determine which report to display
$report = $_GET['report'] ?? 'waiting_list';

if ($report === 'waiting_list') {
    echo "<h3>Students on Waiting List</h3>";
    $result = $conn->query("SELECT banner_number, first_name, last_name, email, current_status 
                            FROM students WHERE current_status = 'Waiting'");
    if ($result->num_rows > 0) {
        echo "<table border='1'>
                <tr><th>Banner Number</th><th>Name</th><th>Email</th><th>Status</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['banner_number']}</td>
                    <td>{$row['first_name']} {$row['last_name']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['current_status']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No students on the waiting list.</p>";
    }
}

if ($report === 'available_rooms') {
    echo "<h3>Available Rooms</h3>";
    $hall_rooms = $conn->query("SELECT hall_rooms.room_number, halls_of_residence.name AS hall_name 
                                FROM hall_rooms 
                                JOIN halls_of_residence ON hall_rooms.hall_id = halls_of_residence.hall_id 
                                WHERE hall_rooms.room_id NOT IN (SELECT room_id FROM leases)");
    $flat_rooms = $conn->query("SELECT flat_rooms.room_number, student_flats.apartment_number 
                                FROM flat_rooms 
                                JOIN student_flats ON flat_rooms.flat_id = student_flats.flat_id 
                                WHERE flat_rooms.room_id NOT IN (SELECT room_id FROM leases)");
    
    echo "<h4>Halls</h4>";
    if ($hall_rooms->num_rows > 0) {
        echo "<table border='1'>
                <tr><th>Room Number</th><th>Hall Name</th></tr>";
        while ($row = $hall_rooms->fetch_assoc()) {
            echo "<tr>
                    <td>Room {$row['room_number']}</td>
                    <td>{$row['hall_name']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No available rooms in halls.</p>";
    }

    echo "<h4>Flats</h4>";
    if ($flat_rooms->num_rows > 0) {
        echo "<table border='1'>
                <tr><th>Room Number</th><th>Apartment Number</th></tr>";
        while ($row = $flat_rooms->fetch_assoc()) {
            echo "<tr>
                    <td>Room {$row['room_number']}</td>
                    <td>{$row['apartment_number']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No available rooms in flats.</p>";
    }
}

if ($report === 'unpaid_invoices') {
    echo "<h3>Unpaid Invoices</h3>";
    $result = $conn->query("SELECT invoices.invoice_id, students.first_name, students.last_name, invoices.payment_due 
                            FROM invoices 
                            JOIN students ON invoices.lease_id = students.student_id
                            WHERE invoices.payment_date IS NULL");
    if ($result->num_rows > 0) {
        echo "<table border='1'>
                <tr><th>Invoice ID</th><th>Student Name</th><th>Amount Due</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['invoice_id']}</td>
                    <td>{$row['first_name']} {$row['last_name']}</td>
                    <td>{$row['payment_due']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No unpaid invoices.</p>";
    }
}

if ($report === 'rent_summary') {
    echo "<h3>Rent Summary</h3>";
    $result = $conn->query("SELECT halls_of_residence.name AS hall_name, 
                            SUM(hall_rooms.monthly_rent) AS total_rent 
                            FROM hall_rooms 
                            JOIN halls_of_residence ON hall_rooms.hall_id = halls_of_residence.hall_id 
                            JOIN leases ON hall_rooms.room_id = leases.room_id 
                            GROUP BY halls_of_residence.name");
    if ($result->num_rows > 0) {
        echo "<table border='1'>
                <tr><th>Hall Name</th><th>Total Rent</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['hall_name']}</td>
                    <td>{$row['total_rent']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No rent collected yet.</p>";
    }
}
?>

<?php include '../includes/footer.php'; ?>
