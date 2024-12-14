

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Accomodation Management System</title>
</head>

<style>
    body {
        margin: 1px;
    }

    h3 {
        margin: 0;
        padding: 10px 20px;
        color: white; 
        font-weight: bold;
    }

    header {
        display: flex;
        justify-content: space-between; 
        background-color: #f1485b; 
        color: white;
        padding: 10px 20px;
    }

    .header-title {
        flex: 1; 
        text-align: left;
        font-size: 24px;
    }

    .Navbar {
        list-style: none;
        display: flex;
        gap: 20px;
        padding: 0;
        margin: 0;
    }

    .Navbar li {
        display: inline-block;
        font-family: Tahoma, sans-serif;
    }

    .Navbar li a {
        text-decoration: none;
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .Navbar li a:hover {
        background-color: white;
        color: #f1485b;
    }
</style>


<body>

    <header>
        <div class="header-title">
            <h3>University Accommodation Management System</h3>
        </div>
    
    <nav class="Navbar">
        <ul>
            <li><a href="/univeracc/index.php">Home</a></li>
            <li><a href="/univeracc/pages/students.php">Manage Students</a></li>
            <li><a href="/univeracc/pages/accommodation.php?type=hall">Manage Halls/Flats</a></li>
            <li><a href="/univeracc/pages/room_assignments.php">Assign Room</a></li>
            <li><a href="/univeracc/pages/leases.php">Manage Leases</a></li>
            <li><a href="/univeracc/pages/reports.php">Reports</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="/univeracc/pages/logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="/univeracc/pages/login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <!-- Include JavaScript file -->
    <script src="../assets/js/students.js"></script>
</header>
</body>
</html>
