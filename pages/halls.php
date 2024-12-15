

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


</body>
</html>
