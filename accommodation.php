
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accommodation</title>
</head>

<style>


    body {
        display: wrap;
        
    }

    h2{
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

<h2>Manage Accommodation</h2>

<!-- Tabs for Halls and Flats -->
 <div class="navbar">
    <nav>
    <a href="?type=hall">Halls of Residence</a> |
    <a href="?type=flat">Student Flats</a>
    </nav>
 </div>


<?php
$type = $_GET['type'] ?? 'hall'; // Default to halls
if ($type === 'hall') {
    include 'halls.php';
} else {
    include 'flats.php';
}
?>

<?php include '../includes/footer.php'; ?>

</body>
</html>


