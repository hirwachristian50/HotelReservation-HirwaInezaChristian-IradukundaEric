<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: auth/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Hotel Reservation</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="dashboard-header">
    <h1>🏨 Hotel Reservation Dashboard</h1>
    <p>Welcome, <?php echo $_SESSION["username"]; ?>!</p>
</div>

<div class="dashboard-grid">

    <div class="card">
        <h3>🛏 Rooms</h3>
        <p>Manage hotel rooms</p>
        <a href="rooms/list.php">View Rooms</a><br>
        <a href="rooms/add.php">Add Room</a>
    </div>

    <div class="card">
        <h3>📅 Bookings</h3>
        <p>Manage reservations</p>
        <a href="bookings/list.php">View Bookings</a><br>
        <a href="bookings/add.php">Add Booking</a>
    </div>

    <div class="card">
        <h3>🔑 Account</h3>
        <p>Session management</p>
        <a href="auth/logout.php">Logout</a>
    </div>

</div>

</body>
</html>
