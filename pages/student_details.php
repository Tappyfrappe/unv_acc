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

<h3>Next-of-Kin Information</h3>
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
<a href="students.php">Back to Student List</a>
