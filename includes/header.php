<header>
    <h1>University Accommodation Management System</h1>
    <nav>
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
