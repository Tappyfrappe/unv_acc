<h3>Manage Student Flats</h3>

<!-- Add Flat Form -->
<form action="" method="post">
    <h4>Add Flat</h4>
    <input type="text" name="apartment_number" placeholder="Apartment Number" required>
    <input type="text" name="address" placeholder="Address" required>
    <input type="number" name="total_bedrooms" placeholder="Total Bedrooms" required>
    <button type="submit" name="add_flat">Add Flat</button>
</form>

<hr>

<!-- Flats List -->
<h4>Flats List</h4>
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
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>
