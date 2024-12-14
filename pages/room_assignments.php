




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<style>

    .mra-container {
        text-align: center;
    }
    .navbar {
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #f9f9f9;
    }

    .navbar nav {
        background-color: #f1485b;
        padding: 10px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
    }

    .navbar nav a {
        text-decoration: none;
        color: white;
        transition: all 0.3s ease;
    }

    .navbar nav a:hover {
        text-decoration: none;
        color: #212f3c;
    }
</style>


<body>
<?php
include '../includes/db_connect.php';
include '../includes/header.php';
?>

<h2 class="mra-container">Manage Room Assignments</h2>

<!-- Tabs for Halls and Flats -->
 <div class="navbar">
    <nav>
    <a href="?type=hall">Assign Rooms in Halls</a> |
    <a href="?type=flat">Assign Rooms in Flats</a>
</nav>
 </div>


<?php
$type = $_GET['type'] ?? 'hall'; // Default to halls
if ($type === 'hall') {
    include 'assign_hall_rooms.php';
} else {
    include 'assign_flat_rooms.php';
}
?>

<?php include '../includes/footer.php'; ?>

</body>
</html>


