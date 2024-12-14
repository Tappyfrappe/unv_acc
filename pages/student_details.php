<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
</head>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
        margin: 0;
        padding: 20px;
    }

    h2, h3 {
        color: #333;
        text-align: center;
        margin-bottom: 20px;
    }

    .details-container, .kin-container {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        margin: 20px auto;
        width: 80%;
        max-width: 600px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
    }

    p {
        color: #555;
        margin: 10px 0;
    }

    p strong {
        color: #222;
    }

    a {
        display: block;
        text-align: center;
        margin-top: 20px;
        padding: 10px 15px;
        background-color: #f1485b;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
        width: fit-content;
        margin-left: auto;
        margin-right: auto;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    a:hover {
        background-color: #fff;
        color: #f1485b;
        border: 1px solid #f1485b;
        
    
    }

   
</style>


<body>
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

// Fetch student details
$student_result = $conn->query("SELECT * FROM students WHERE student_id = $student_id");

if (!$student_result || $student_result->num_rows === 0) {
    die("Student not found.");
}

$student = $student_result->fetch_assoc();

// Fetch next-of-kin details
$kin_result = $conn->query("SELECT * FROM next_of_kin WHERE student_id = $student_id");
?>

<h2>Student Details</h2>
<div class="details-container">
    <p><strong>Banner Number:</strong> <?php echo $student['banner_number']; ?></p>
    <p><strong>Name:</strong> <?php echo "{$student['first_name']} {$student['last_name']}"; ?></p>
    <p><strong>Email:</strong> <?php echo $student['email']; ?></p>
    <p><strong>Phone:</strong> <?php echo $student['mobile_phone']; ?></p>
    <p><strong>Address:</strong> <?php echo $student['home_address']; ?></p>
    <p><strong>Date of Birth:</strong> <?php echo $student['dob']; ?></p>
    <p><strong>Gender:</strong> <?php echo $student['gender']; ?></p>
    <p><strong>Category:</strong> <?php echo $student['student_category']; ?></p>
    <p><strong>Special Needs:</strong> <?php echo $student['special_needs']; ?></p>
    <p><strong>Additional Comments:</strong> <?php echo $student['additional_comments']; ?></p>
    <p><strong>Status:</strong> <?php echo $student['current_status']; ?></p>
</div>


<h3>Next-of-Kin Information</h3> 
<div class="kin-container">
   
<?php
if ($kin_result->num_rows > 0) {
    while ($kin = $kin_result->fetch_assoc()) {
        echo "<p><strong>Name:</strong> {$kin['name']}</p>";
        echo "<p><strong>Relationship:</strong> {$kin['relationship']}</p>";
        echo "<p><strong>Phone:</strong> {$kin['contact_phone']}</p>";
        echo "<p><strong>Address:</strong> {$kin['address']}</p>";
    }
} else {
    echo "<p>No next-of-kin information found.</p>";
}
?>

</div>
<a href="students.php">Back to Student List</a>

</body>
</html>


