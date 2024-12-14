

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to the University Accommodation Management System</title>
</head>

<style>
    .navigator {
        width: 80%;
        max-width: 600px;
        margin: 30px auto;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f9f9f9;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        text-align: center;
        font-family: Arial, sans-serif;
    }

    .navigator h2 {
        margin-bottom: 15px;
        color: #333;
        font-size: 1.5em;
    }

    .navigator p {
        margin-bottom: 20px;
        color: #555;
        font-size: 1em;
    }

    .navigator ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .navigator ul li {
        margin: 10px 0;
    }

    .navigator ul li a {
        text-decoration: none;
        color: #f1485b;
        font-size: 1em;
        padding: 10px 20px;
        border: 1px solid #f1485b;
        border-radius: 4px;
        display: inline-block;
        transition: all 0.3s ease;
        background-color: #fff;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .navigator ul li a:hover {
        background-color: #f1485b;
        color: #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
        transform: translateY(-3px);
    }
</style>

<body>
    <?php include 'includes/header.php'; ?>

    
    <div class="navigator">
        <h2>Welcome to the University Accommodation Management System</h2>
        <p>Use the links below to navigate:</p>

        <ul>
            <li><a href="pages/students.php">Manage Students</a></li>
            <li><a href="pages/accommodation.php?type=hall">Manage Halls of Residence</a></li>
            <li><a href="pages/accommodation.php?type=flat">Manage Student Flats</a></li>
            <li><a href="pages/room_assignments.php">Manage Room Assignments</a></li>
            <li><a href="pages/manage_flat_rooms.php">Manage Flat Rooms</a></li>
            <li><a href="pages/reports.php">Reports</a></li>
            <li><a href="pages/leases.php">Manage Leases</a></li>
        </ul>
    </div>
    

<?php include 'includes/footer.php'; ?>
</body>
</html>

