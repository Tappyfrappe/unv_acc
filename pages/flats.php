
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flats</title>
</head>


<style>

.student-flats-container {
        width: 80%;
        max-width: 600px;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        background-color: #f4f4f4;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        text-align: center;
        font-family: Arial, sans-serif;
    }

    .student-flats-container h3 {
        margin-bottom: 20px;
        color: #333;
        font-size: 1.5em;
    }

    .student-flats-container h4 {
        margin-bottom: 15px;
        color: #555;
        font-size: 1.2em;
    }

    .student-flats-container form {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
    }

    .student-flats-container input,
    .student-flats-container button {
        width: 90%;
        max-width: 400px;
        padding: 10px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .student-flats-container input:focus {
        outline: none;
        border-color: #f1485b;
        box-shadow: 0 0 4px rgba(6, 6, 6, 0.5);
    }

    .student-flats-container button {
        background-color: #f1485b;
        color: #fff;
        border: none;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .student-flats-container button:hover {
        background-color: white;
        color: #f1485b;
        border: 1px solid #f1485b;
    }
     .fl-cont {
        color: black;
        text-align: center;
     }


    table {
        margin: 0 auto; 
        border-collapse: collapse; 
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
</style>


<body>
<div class="student-flats-container">
    <h3>Manage Student Flats</h3>
    <!-- Add Flat Form -->
    <form action="" method="post">
        <h4>Add Flat</h4>
        <input type="text" name="apartment_number" placeholder="Apartment Number" required>
        <input type="text" name="address" placeholder="Address" required>
        <input type="number" name="total_bedrooms" placeholder="Total Bedrooms" required>
        <button type="submit" name="add_flat">Add Flat</button>
    </form>
</div>


<hr>

<!-- Flats List -->
<h4 class="fl-cont">Flats List</h4>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Apartment Number</th>
        <th>Address</th>
        <th>Total Bedrooms</th>
        <th>Actions</th>
    </tr>
    <?php
    // Fetch flats
    $sql = "SELECT * FROM student_flats";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['flat_id']}</td>
                <td>{$row['apartment_number']}</td>
                <td>{$row['address']}</td>
                <td>{$row['total_bedrooms']}</td>
                <td>
                    <a href='?type=flat&edit={$row['flat_id']}'>Edit</a>
                    <a href='?type=flat&delete={$row['flat_id']}'>Delete</a>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No flats found</td></tr>";
    }
    ?>
</table>

<?php
// Add Flat Logic
if (isset($_POST['add_flat'])) {
    $apartment_number = $_POST['apartment_number'];
    $address = $_POST['address'];
    $total_bedrooms = $_POST['total_bedrooms'];

    $sql = "INSERT INTO student_flats (apartment_number, address, total_bedrooms)
            VALUES ('$apartment_number', '$address', $total_bedrooms)";

    if ($conn->query($sql) === TRUE) {
        echo "Flat added successfully.";
        header("Location: accommodation.php?type=flat");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Delete Flat Logic
if (isset($_GET['delete'])) {
    $flat_id = $_GET['delete'];

    $sql = "DELETE FROM student_flats WHERE flat_id = $flat_id";

    if ($conn->query($sql) === TRUE) {
        echo "Flat deleted successfully.";
        header("Location: accommodation.php?type=flat");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>
</body>
</html>
