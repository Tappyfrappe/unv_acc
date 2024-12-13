<?php
include '../includes/db_connect.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// User data
$username = "admin"; // Change this to the desired username
$password = "password123"; // Change this to the desired password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$role = "admin"; // Optional: Use 'admin' or other roles if applicable

// Insert user into the database
$stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("sss", $username, $hashed_password, $role);

if ($stmt->execute()) {
    echo "User '$username' created successfully.";
} else {
    echo "Error: " . $conn->error;
}
?>
