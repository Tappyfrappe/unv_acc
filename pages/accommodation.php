<?php
include '../includes/db_connect.php';
include '../includes/header.php';
?>

<h2>Manage Accommodation</h2>

<!-- Tabs for Halls and Flats -->
<nav>
    <a href="?type=hall">Halls of Residence</a> |
    <a href="?type=flat">Student Flats</a>
</nav>

<?php
$type = $_GET['type'] ?? 'hall'; // Default to halls
if ($type === 'hall') {
    include 'halls.php';
} else {
    include 'flats.php';
}
?>

<?php include '../includes/footer.php'; ?>
