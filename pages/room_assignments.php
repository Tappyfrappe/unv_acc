<?php
include '../includes/db_connect.php';
include '../includes/header.php';
?>

<h2>Manage Room Assignments</h2>

<!-- Tabs for Halls and Flats -->
<nav>
    <a href="?type=hall">Assign Rooms in Halls</a> |
    <a href="?type=flat">Assign Rooms in Flats</a>
</nav>

<?php
$type = $_GET['type'] ?? 'hall'; // Default to halls
if ($type === 'hall') {
    include 'assign_hall_rooms.php';
} else {
    include 'assign_flat_rooms.php';
}
?>

<?php include '../includes/footer.php'; ?>
