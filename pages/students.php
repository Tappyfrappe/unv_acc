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

// Handle Create, Read, Update, and Delete here
?>

<h2>Manage Students</h2>

<!-- Form to Add New Student -->
<form action="" method="post">
    <h3>Add Student</h3>
    <input type="text" name="banner_number" placeholder="Banner Number" required>
    <input type="text" name="first_name" placeholder="First Name" required>
    <input type="text" name="last_name" placeholder="Last Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="mobile_phone" placeholder="Mobile Phone">
    <input type="text" name="home_address" placeholder="Home Address">
    <input type="date" name="dob" placeholder="Date of Birth">
    <select name="gender">
        <option value="">Select Gender</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Other">Other</option>
    </select>
    <select name="student_category" required>
        <option value="">Select Category</option>
        <option value="Undergraduate">Undergraduate</option>
        <option value="Postgraduate">Postgraduate</option>
    </select>
    <textarea name="special_needs" placeholder="Special Needs"></textarea>
    <textarea name="additional_comments" placeholder="Additional Comments"></textarea>

    <h3>Next-of-Kin Information</h3>
    <label for="kin_name">Name:</label>
    <input type="text" name="kin_name" id="kin_name" required>

    <label for="relationship">Relationship:</label>
    <input type="text" name="relationship" id="relationship" required>

    <label for="contact_phone">Phone:</label>
    <input type="text" name="contact_phone" id="contact_phone" required>

    <label for="kin_address">Address:</label>
    <textarea name="kin_address" id="kin_address"></textarea>

    <button type="submit" name="add_student">Add Student</button>


</form>

<hr>

<!-- Display Students -->
<h3>Student List</h3>
<table border="1">
    <tr>
        <th>Banner Number</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>

    <?php
    $sql = "SELECT student_id, banner_number, first_name, last_name, email, current_status FROM students";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
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
        echo "<tr><td colspan='6'>No students found</td></tr>";
    }
    ?>
</table>



<?php 

if (isset($_POST['add_student'])) {
    $banner_number = $_POST['banner_number'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $mobile_phone = $_POST['mobile_phone'];
    $home_address = $_POST['home_address'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $student_category = $_POST['student_category'];

    // Next-of-Kin Fields
    $kin_name = $_POST['kin_name'];
    $relationship = $_POST['relationship'];
    $contact_phone = $_POST['contact_phone'];
    $kin_address = $_POST['kin_address'];

    // Insert student
    $sql = "INSERT INTO students (banner_number, first_name, last_name, email, mobile_phone, home_address, dob, gender, student_category, current_status)
            VALUES ('$banner_number', '$first_name', '$last_name', '$email', '$mobile_phone', '$home_address', '$dob', '$gender', '$student_category', 'Waiting')";

    if ($conn->query($sql) === TRUE) {
        $student_id = $conn->insert_id; // Get the student ID

        // Insert next-of-kin
        $kin_sql = "INSERT INTO next_of_kin (student_id, name, relationship, contact_phone, address)
                    VALUES ($student_id, '$kin_name', '$relationship', '$contact_phone', '$kin_address')";
        if ($conn->query($kin_sql) === TRUE) {
            echo "<p>Student and next-of-kin added successfully.</p>";
            exit();
        } else {
            echo "<p>Error adding next-of-kin: " . $conn->error . "</p>";
        }
    } else {
        echo "<p>Error adding student: " . $conn->error . "</p>";
    }
}





if (isset($_GET['edit'])) {
    $student_id = $_GET['edit'];

    // Fetch student details
    $result = $conn->query("SELECT * FROM students WHERE student_id = $student_id");
    $student = $result->fetch_assoc();

    // Fetch next-of-kin details
    $kin_result = $conn->query("SELECT * FROM next_of_kin WHERE student_id = $student_id");
    $kin = $kin_result->fetch_assoc();
?>

<!-- Form to Edit Student -->
<form action="" method="post">
    <h3>Edit Student</h3>
    <input type="hidden" name="student_id" value="<?php echo $student['student_id']; ?>">

    <label for="banner_number">Banner Number:</label>
    <input type="text" name="banner_number" id="banner_number" value="<?php echo $student['banner_number']; ?>" required>

    <label for="first_name">First Name:</label>
    <input type="text" name="first_name" id="first_name" value="<?php echo $student['first_name']; ?>" required>

    <label for="last_name">Last Name:</label>
    <input type="text" name="last_name" id="last_name" value="<?php echo $student['last_name']; ?>" required>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" value="<?php echo $student['email']; ?>" required>

    <label for="mobile_phone">Mobile Phone:</label>
    <input type="text" name="mobile_phone" id="mobile_phone" value="<?php echo $student['mobile_phone']; ?>">

    <label for="home_address">Home Address:</label>
    <input type="text" name="home_address" id="home_address" value="<?php echo $student['home_address']; ?>">

    <label for="dob">Date of Birth:</label>
    <input type="date" name="dob" id="dob" value="<?php echo $student['dob']; ?>">

    <label for="gender">Gender:</label>
    <select name="gender" id="gender">
        <option value="">Select Gender</option>
        <option value="Male" <?php echo ($student['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
        <option value="Female" <?php echo ($student['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
        <option value="Other" <?php echo ($student['gender'] === 'Other') ? 'selected' : ''; ?>>Other</option>
    </select>

    <label for="student_category">Student Category:</label>
    <select name="student_category" id="student_category" required>
        <option value="">Select Category</option>
        <option value="Undergraduate" <?php echo ($student['student_category'] === 'Undergraduate') ? 'selected' : ''; ?>>Undergraduate</option>
        <option value="Postgraduate" <?php echo ($student['student_category'] === 'Postgraduate') ? 'selected' : ''; ?>>Postgraduate</option>
    </select>

    <label for="special_needs">Special Needs:</label>
    <textarea name="special_needs" id="special_needs" placeholder="Special Needs"><?php echo $student['special_needs']; ?></textarea>

    <label for="additional_comments">Additional Comments:</label>
    <textarea name="additional_comments" id="additional_comments" placeholder="Additional Comments"><?php echo $student['additional_comments']; ?></textarea>

    <!-- Next-of-Kin Details -->
    <h4>Next-of-Kin Information</h4>
    <input type="hidden" name="kin_id" value="<?php echo $kin['kin_id']; ?>">

    <label for="kin_name">Name:</label>
    <input type="text" name="kin_name" id="kin_name" value="<?php echo $kin['name']; ?>" required>

    <label for="relationship">Relationship:</label>
    <input type="text" name="relationship" id="relationship" value="<?php echo $kin['relationship']; ?>" required>

    <label for="contact_phone">Phone:</label>
    <input type="text" name="contact_phone" id="contact_phone" value="<?php echo $kin['contact_phone']; ?>" required>

    <label for="kin_address">Address:</label>
    <textarea name="kin_address" id="kin_address"><?php echo $kin['address']; ?></textarea>


    <button type="submit" name="update_student">Update Student</button>
</form>
<?php
}


if (isset($_GET['delete'])) {
    $student_id = $_GET['delete'];

    // Delete next-of-kin records first
    $kin_sql = "DELETE FROM next_of_kin WHERE student_id = $student_id";
    if ($conn->query($kin_sql) === TRUE) {
        // Delete the student record
        $student_sql = "DELETE FROM students WHERE student_id = $student_id";
        if ($conn->query($student_sql) === TRUE) {
            exit();
        } else {
            echo "Error deleting student record: " . $conn->error;
        }
    } else {
        echo "Error deleting next-of-kin record: " . $conn->error;
    }
}


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

    // Update student table
    $sql = "UPDATE students 
            SET banner_number = '$banner_number', 
                first_name = '$first_name', 
                last_name = '$last_name', 
                email = '$email', 
                mobile_phone = '$mobile_phone', 
                home_address = '$home_address', 
                dob = '$dob', 
                gender = '$gender', 
                student_category = '$student_category', 
                special_needs = '$special_needs', 
                additional_comments = '$additional_comments'
            WHERE student_id = $student_id";

    if ($conn->query($sql) === TRUE) {
        // Next-of-Kin details
        $kin_id = $_POST['kin_id'];
        $kin_name = $_POST['kin_name'];
        $relationship = $_POST['relationship'];
        $contact_phone = $_POST['contact_phone'];
        $kin_address = $_POST['kin_address'];

        // Update next_of_kin table
        $kin_sql = "UPDATE next_of_kin 
                    SET name = '$kin_name', 
                        relationship = '$relationship', 
                        contact_phone = '$contact_phone', 
                        address = '$kin_address' 
                    WHERE kin_id = $kin_id";

        if ($conn->query($kin_sql) === TRUE) {
            exit();
        } else {
            echo "Error updating next-of-kin: " . $conn->error;
        }
    } else {
        echo "Error updating student: " . $conn->error;
    }
}






include '../includes/footer.php'; 
?>
