
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hall Of Residence</title>
</head>

<style>

.addform-container {
        width: 80%;
        max-width: 600px;
        margin: 20px auto;
        padding: 20px;
        border-radius: 8px;
        background-color: #f9f9f9;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .addform-container h3, 
    .addform-container h4 {

        text-align: center;
        color: #333;
        font-family: Arial, sans-serif;
        margin: 0 0 10px;
        margin-top: 10px
    }

    .addform-container form {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
        width: 100%;
    }

    .addform-container input, 
    .addform-container select, 
    .addform-container button {
        width: 90%;
        max-width: 400px;
        padding: 10px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .addform-container input:focus, 
    .addform-container select:focus {
        outline: none;
        border-color: #f1485b;
        box-shadow: 0 0 4px rgba(0, 123, 255, 0.5);
    }

    .addform-container button {
        background-color: #f1485b;
        color: white;
        cursor: pointer;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    .addform-container button:hover {
        background-color: white;
        color: #f1485b;
        border: 1px solid #f1485b;
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

    .halls_list_container, .rooms-in-halls-container {
        text-align: center;
        color: black;
    }


</style>



<body>
    

<!-- Add Hall Form -->
<div class="addform-container">
    <h3 class="mhr-container">Manage Halls of Residence</h3>
    <form action="" method="post">
    <h4>Add Hall</h4>
    <input type="text" name="name" placeholder="Hall Name" required>
    <input type="text" name="address" placeholder="Address" required>
    <input type="text" name="telephone" placeholder="Telephone" required>
    <input type="text" name="manager_name" placeholder="Manager Name" required>
    <button type="submit" name="add_hall">Add Hall</button>
    </form>



    <h4>Add Rooms to Hall</h4>
    <form action="" method="post">
        <select name="hall_id" required>
            <option value="">Select Hall</option>
            <?php
            // Fetch halls to populate dropdown
            $sql = "SELECT * FROM halls_of_residence";
            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['hall_id']}'>{$row['name']}</option>";
            }
            ?>
        </select>
        <input type="text" name="place_number" placeholder="Place Number" required>
        <input type="text" name="room_number" placeholder="Room Number" required>
        <input type="text" name="monthly_rent" placeholder="Monthly Rent" required>
        <button type="submit" name="add_room">Add Room</button>
    </form>
</div>

<hr>

<!-- Hall List -->
<h4 class="halls_list_container">Halls List</h4>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Address</th>
        <th>Telephone</th>
        <th>Manager</th>
        <th>Actions</th>
    </tr>
    <?php
    // Fetch halls
    $sql = "SELECT * FROM halls_of_residence";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['hall_id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['address']}</td>
                <td>{$row['telephone']}</td>
                <td>{$row['manager_name']}</td>
                <td>
                    <a href='?type=hall&edit={$row['hall_id']}'>Edit</a>
                    <a href='?type=hall&delete={$row['hall_id']}'>Delete</a>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No halls found</td></tr>";
    }
    ?>
</table>

<!-- Display Assigned Rooms -->
<h4 class="rooms-in-halls-container">Rooms in Halls</h4>
<table border="1">
    <tr>
        <th>Room ID</th>
        <th>Place Number</th>
        <th>Room Number</th>
        <th>Monthly Rent</th>
        <th>Hall Name</th>
        <th>Actions</th>
    </tr>

    <?php
    $sql = "SELECT hr.room_id, hr.place_number, hr.room_number, hr.monthly_rent, h.name AS hall_name
            FROM hall_rooms hr
            JOIN halls_of_residence h ON hr.hall_id = h.hall_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['room_id']}</td>
                <td>{$row['place_number']}</td>
                <td>{$row['room_number']}</td>
                <td>{$row['monthly_rent']}</td>
                <td>{$row['hall_name']}</td>
                <td>
                    <a href='?edit_room={$row['room_id']}'>Edit</a>
                    <a href='?delete_room={$row['room_id']}'>Delete</a>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No rooms found</td></tr>";
    }

    if (isset($_GET['edit_room'])) {
        $room_id = $_GET['edit_room'];
    
        // Fetch room details
        $sql = "SELECT * FROM hall_rooms WHERE room_id = $room_id";
        $result = $conn->query($sql);
    
        if ($result->num_rows > 0) {
            $room = $result->fetch_assoc();
            ?>
            <h4>Edit Room</h4>
            <form action="" method="post">
                <input type="hidden" name="room_id" value="<?php echo $room['room_id']; ?>">
                <input type="text" name="place_number" value="<?php echo $room['place_number']; ?>" required>
                <input type="text" name="room_number" value="<?php echo $room['room_number']; ?>" required>
                <input type="text" name="monthly_rent" value="<?php echo $room['monthly_rent']; ?>" required>
                <select name="hall_id" required>
                    <option value="">Select Hall</option>
                    <?php
                    // Fetch all halls
                    $sql = "SELECT * FROM halls_of_residence";
                    $halls = $conn->query($sql);
    
                    while ($hall = $halls->fetch_assoc()) {
                        $selected = $hall['hall_id'] == $room['hall_id'] ? 'selected' : '';
                        echo "<option value='{$hall['hall_id']}' $selected>{$hall['name']}</option>";
                    }
                    ?>
                </select>
                <button type="submit" name="update_room">Update Room</button>
            </form>
            <?php
        } else {
            echo "Room not found.";
        }
    }
    

    if (isset($_GET['delete_room'])) {
        $room_id = intval($_GET['delete_room']); // Sanitize input
        $stmt = $conn->prepare("DELETE FROM hall_rooms WHERE room_id = ?");
        $stmt->bind_param("i", $room_id);
        if ($stmt->execute()) {
            echo "Room deleted successfully.";
            exit();
        } else {
            echo "Error deleting room.";
        }
        $stmt->close();
    }
    

    if (isset($_POST['update_room'])) {
        $room_id = $_POST['room_id'];
        $place_number = $_POST['place_number'];
        $room_number = $_POST['room_number'];
        $monthly_rent = $_POST['monthly_rent'];
        $hall_id = $_POST['hall_id'];
    
        $sql = "UPDATE hall_rooms 
                SET place_number = '$place_number', room_number = '$room_number', 
                    monthly_rent = '$monthly_rent', hall_id = $hall_id 
                WHERE room_id = $room_id";
    
        if ($conn->query($sql) === TRUE) {
            echo "Room updated successfully.";
            exit();
        } else {
            echo "Error updating room: " . $conn->error;
        }
    }
    
    
    ?>
</table>


<?php
// Add Hall Logic
if (isset($_POST['add_hall'])) {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $telephone = $_POST['telephone'];
    $manager_name = $_POST['manager_name'];

    $sql = "INSERT INTO halls_of_residence (name, address, telephone, manager_name)
            VALUES ('$name', '$address', '$telephone', '$manager_name')";

    if ($conn->query($sql) === TRUE) {
        echo "Hall added successfully.";
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Delete Hall Logic
if (isset($_GET['delete'])) {
    $hall_id = $_GET['delete'];

    $sql = "DELETE FROM halls_of_residence WHERE hall_id = $hall_id";

    if ($conn->query($sql) === TRUE) {
        echo "Hall deleted successfully.";
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

if (isset($_POST['add_room'])) {
    $hall_id = intval($_POST['hall_id']);
    $place_number = trim($_POST['place_number']);
    $room_number = trim($_POST['room_number']);
    $monthly_rent = floatval($_POST['monthly_rent']); // Ensure rent is a valid decimal

    // Validate inputs
    if ($hall_id > 0 && !empty($place_number) && !empty($room_number) && $monthly_rent > 0) {
        // Insert room into the database
        $stmt = $conn->prepare("INSERT INTO hall_rooms (place_number, hall_id, room_number, monthly_rent) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sisd", $place_number, $hall_id, $room_number, $monthly_rent);

        if ($stmt->execute()) {
            echo "<p>Room added successfully.</p>";
        } else {
            echo "<p>Error adding room: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p>Please fill in all fields correctly.</p>";
    }
}


?>
</body>
</html>
