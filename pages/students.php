<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit();
}

include '../includes/db_connect.php';
include '../includes/header.php';

$error_message = ''; // Initialize error message variable
$success_message = ''; // Initialize success message variable

// Handle Add Student
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_student'])) {
    $banner_number = $_POST['banner_number'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $mobile_phone = $_POST['mobile_phone'];
    $home_address = $_POST['home_address'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $student_category = $_POST['student_category'];
    $special_needs = $_POST['special_needs'];
    $additional_comments = $_POST['additional_comments'];

    $kin_name = $_POST['kin_name'];
    $relationship = $_POST['relationship'];
    $contact_phone = $_POST['contact_phone'];
    $kin_address = $_POST['kin_address'];

    try {
        ob_start();
        $conn->begin_transaction();

        $student_stmt = $conn->prepare(
            "INSERT INTO students (banner_number, first_name, last_name, email, mobile_phone, home_address, dob, gender, student_category, special_needs, additional_comments) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $student_stmt->bind_param(
            "sssssssssss",
            $banner_number, $first_name, $last_name, $email, $mobile_phone, $home_address, $dob, $gender, $student_category, $special_needs, $additional_comments
        );

        if ($student_stmt->execute()) {
            $student_id = $conn->insert_id;

            $kin_stmt = $conn->prepare(
                "INSERT INTO next_of_kin (student_id, name, relationship, contact_phone, address) 
                 VALUES (?, ?, ?, ?, ?)"
            );
            $kin_stmt->bind_param("issss", $student_id, $kin_name, $relationship, $contact_phone, $kin_address);

            if ($kin_stmt->execute()) {
                $conn->commit();
                ob_end_flush();
                header("Location: students.php?success=1");
                exit();
            } else {
                throw new Exception("Failed to insert next-of-kin: " . $kin_stmt->error);
            }
        }
    } catch (Exception $e) {
        $conn->rollback();  
        $error_message = $e->getMessage();
    }
}

// Handle Edit Student
if (isset($_GET['edit'])) {
    $student_id = $_GET['edit'];

    // Fetch student details
    $result = $conn->query("SELECT * FROM students WHERE student_id = $student_id");
    $student = $result->fetch_assoc();

    // Fetch next-of-kin details
    $kin_result = $conn->query("SELECT * FROM next_of_kin WHERE student_id = $student_id");
    $kin = $kin_result->fetch_assoc();
}

// Handle Update Student
if (isset($_POST['update_student'])) {
    $student_id = $_POST['student_id'];
    $banner_number = $_POST['banner_number'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $mobile_phone = $_POST['mobile_phone'];
    $home_address = $_POST['home_address'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $student_category = $_POST['student_category'];
    $special_needs = $_POST['special_needs'];
    $additional_comments = $_POST['additional_comments'];

    $kin_name = $_POST['kin_name'];
    $relationship = $_POST['relationship'];
    $contact_phone = $_POST['contact_phone'];
    $kin_address = $_POST['kin_address'];

    try {
        $conn->begin_transaction();

        // Update student details
        $update_student_stmt = $conn->prepare(
            "UPDATE students SET banner_number = ?, first_name = ?, last_name = ?, email = ?, 
            mobile_phone = ?, home_address = ?, dob = ?, gender = ?, student_category = ?, special_needs = ?, 
            additional_comments = ? WHERE student_id = ?"
        );
        $update_student_stmt->bind_param(
            "sssssssssssi",
            $banner_number, $first_name, $last_name, $email, $mobile_phone, $home_address, $dob, $gender, 
            $student_category, $special_needs, $additional_comments, $student_id
        );
        $update_student_stmt->execute();

        // Update next-of-kin details
        $update_kin_stmt = $conn->prepare(
            "UPDATE next_of_kin SET name = ?, relationship = ?, contact_phone = ?, address = ? WHERE student_id = ?"
        );
        $update_kin_stmt->bind_param(
            "ssssi", 
            $kin_name, $relationship, $contact_phone, $kin_address, $student_id
        );
        $update_kin_stmt->execute();

        $conn->commit();
        header("Location: students.php?success=2");
        exit();
    } catch (Exception $e) {
        $conn->rollback();  
        $error_message = $e->getMessage();
    }
}

// Handle Delete Student
if (isset($_GET['delete'])) {
    $student_id = $_GET['delete'];

    // Delete related next-of-kin records first
    $conn->query("DELETE FROM next_of_kin WHERE student_id = $student_id");

    // Delete the student record
    if ($conn->query("DELETE FROM students WHERE student_id = $student_id")) {
        header("Location: students.php?success=3");
        exit();
    } else {
        $error_message = "Error deleting student: " . $conn->error;
    }
}
?>

<h3 class="h3-studentlist">Student List</h3>
<table>
    <tr>
        <th>Banner Number</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    <?php
    // Define the SQL query
    $sql = "SELECT student_id, banner_number, first_name, last_name, email, current_status FROM students";
    $result = $conn->query($sql);

    // Check if the query returned any results
    if ($result && $result->num_rows > 0) {
        // Loop through each row and display the student details
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['banner_number']}</td>
                <td>{$row['first_name']}</td>
                <td>{$row['last_name']}</td>
                <td>{$row['email']}</td>
                <td>{$row['current_status']}</td>
                <td>
                    <a href='student_details.php?student_id={$row['student_id']}'>See More</a>
                    <a href='students.php?edit={$row['student_id']}'>Edit</a>
                    <a href='students.php?delete={$row['student_id']}'>Delete</a>
                </td>
            </tr>
            <tr id='details-{$row['student_id']}' style='display:none;'>
                <td colspan='6' id='details-content-{$row['student_id']}'></td>
            </tr>";
        }
    } else {
        // Display a message if no students are found
        echo "<tr><td colspan='6'>No students found</td></tr>";
    }
    ?>
</table>
<?php
if (isset($_GET['success'])) {
    if ($_GET['success'] == 1) {
        $success_message = 'Student added successfully!';
    } elseif ($_GET['success'] == 2) {
        $success_message = 'Student updated successfully!';
    } elseif ($_GET['success'] == 3) {
        $success_message = 'Student deleted successfully!';
    }
} else {
    $success_message = ''; // Default to empty
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Accommodation Management System - Manage Students</title>
    <style>
        .h2-header {
            text-align: center;
        }

        .form-section {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #ffffff;
            width: 50%;
            margin: 30px auto;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        input, select, textarea, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }

        button {
            background-color: #f1485b;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: white;
            color: #f1485b;
            border: 1px solid #f1485b;
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

        .success-message {
            padding: 10px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            margin: 20px auto;
            width: 80%;
            text-align: center;
            border-radius: 5px;
        }

        .error-message {
            padding: 10px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            margin: 20px auto;
            width: 80%;
            text-align: center;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    

<?php if (!empty($success_message)): ?>
    <div class="success-message"><?php echo $success_message; ?></div>
<?php endif; ?>

<?php if (!empty($error_message)): ?>
    <div class="error-message"><?php echo $error_message; ?></div>
<?php endif; ?>

<h2 class="h2-header">Manage Students</h2>
<div class="form-section">
    <form action="" method="POST">
        <h3><?php echo isset($student) ? 'Edit Student' : 'Add Student'; ?></h3>

        <input type="hidden" name="student_id" value="<?php echo isset($student) ? $student['student_id'] : ''; ?>">

        <label for="banner_number">Banner Number:</label>
        <input type="text" name="banner_number" value="<?php echo isset($student) ? $student['banner_number'] : ''; ?>" required>

        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" value="<?php echo isset($student) ? $student['first_name'] : ''; ?>" required>

        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" value="<?php echo isset($student) ? $student['last_name'] : ''; ?>" required>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo isset($student) ? $student['email'] : ''; ?>" required>

        <label for="mobile_phone">Mobile Phone:</label>
        <input type="text" name="mobile_phone" value="<?php echo isset($student) ? $student['mobile_phone'] : ''; ?>" required>

        <label for="home_address">Home Address:</label>
        <textarea name="home_address" required><?php echo isset($student) ? $student['home_address'] : ''; ?></textarea>

        <label for="dob">Date of Birth:</label>
        <input type="date" name="dob" value="<?php echo isset($student) ? $student['dob'] : ''; ?>" required>

        <label for="gender">Gender:</label>
        <select name="gender" required>
            <option value="Male" <?php echo isset($student) && $student['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
            <option value="Female" <?php echo isset($student) && $student['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
        </select>

        <label for="student_category">Category:</label>
        <select name="student_category" required>
            <option value="Undergraduate" <?php echo isset($student) && $student['student_category'] == 'Undergraduate' ? 'selected' : ''; ?>>Undergraduate</option>
            <option value="Postgraduate" <?php echo isset($student) && $student['student_category'] == 'Postgraduate' ? 'selected' : ''; ?>>Postgraduate</option>
        </select>

        <label for="special_needs">Special Needs:</label>
        <input type="text" name="special_needs" value="<?php echo isset($student) ? $student['special_needs'] : ''; ?>">

        <label for="additional_comments">Additional Comments:</label>
        <textarea name="additional_comments"><?php echo isset($student) ? $student['additional_comments'] : ''; ?></textarea>

        <h4>Next of Kin Details</h4>
        <label for="kin_name">Name:</label>
        <input type="text" name="kin_name" value="<?php echo isset($kin) ? $kin['name'] : ''; ?>" required>

        <label for="relationship">Relationship:</label>
        <input type="text" name="relationship" value="<?php echo isset($kin) ? $kin['relationship'] : ''; ?>" required>

        <label for="contact_phone">Contact Phone:</label>
        <input type="text" name="contact_phone" value="<?php echo isset($kin) ? $kin['contact_phone'] : ''; ?>" required>

        <label for="kin_address">Address:</label>
        <textarea name="kin_address" required><?php echo isset($kin) ? $kin['address'] : ''; ?></textarea>

        <button type="submit" name="<?php echo isset($student) ? 'update_student' : 'add_student'; ?>">
            <?php echo isset($student) ? 'Update Student' : 'Add Student'; ?>
        </button>
    </form>
</div>

<?php if (!empty($success_message)): ?>
    <div class="alert success" id="success-alert"><?php echo $success_message; ?></div>
<?php endif; ?>

<script>
    setTimeout(() => {
        const alert = document.getElementById('success-alert');
        if (alert) alert.style.display = 'none';
    }, 5000);
</script>

</body>
</html>
