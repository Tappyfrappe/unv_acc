<?php
include '../includes/db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_GET['student_id'] ?? null;

if (!$student_id) {
    die("Student ID is missing.");
}

// Query for next-of-kin details
$result = $conn->query("SELECT * FROM next_of_kin WHERE student_id = $student_id");

if (!$result) {
    die("Query failed: " . $conn->error);
}

echo "<h2>Next-of-Kin Details</h2>";

if ($result->num_rows > 0) {
    while ($kin = $result->fetch_assoc()) {
        echo "<p>Name: {$kin['name']}</p>";
        echo "<p>Relationship: {$kin['relationship']}</p>";
        echo "<p>Phone: {$kin['contact_phone']}</p>";
        echo "<p>Address: {$kin['address']}</p>";
        echo "<a href='edit_kin.php?kin_id={$kin['kin_id']}'>Edit Next-of-Kin</a>";
    }
} else {
    echo "<p>No next-of-kin information found for this student.</p>";
}
?>
