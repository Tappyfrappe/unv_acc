<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$dbname = "unv_acc";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
