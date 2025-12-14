<?php
session_start();

// Protect page
if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/database.php";

try {
    $stmt = $conn->prepare(
        "SELECT 
            bookings.id,
            users.username,
            rooms.room_number,
            bookings.check_in,
            bookings.check_out,
            bookings.status
         FROM bookings
         INNER JOIN users ON bookings.user_id = users.id
         INNER JOIN rooms ON bookings.room_id = rooms.id
         ORDER BY bookings.id DESC"
    );

    $stmt->execute();
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error loading bookings.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Bookings</title>
    <link rel="stylesheet" href="../assets/css/style.css">

</head>
<body>
    <div class="container"></div>


<h2>Bookings List</h2>

<a href="add.php">Add Booking</a> |
<a href="../index.php">Dashboard</a>

<br><br>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>User</th>
        <th>Room</th>
        <th>Check-in</th>
        <th>Check-out</th>
        <th>Status</th>
        <th>Action</th>
    </tr>

    <?php if ($bookings): ?>
        <?php foreach ($bookings as $booking): ?>
            <tr>
                <td><?php echo $booking["id"]; ?></td>
                <td><?php echo $booking["username"]; ?></td>
                <td>Room <?php echo $booking["room_number"]; ?></td>
                <td><?php echo $booking["check_in"]; ?></td>
                <td><?php echo $booking["check_out"]; ?></td>
                <td><?php echo $booking["status"]; ?></td>
                <td>
                    <a href="approve.php?id=<?php echo $booking["id"]; ?>">
                        Approve
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="7">No bookings found.</td>
        </tr>
    <?php endif; ?>

</table>

</body>
</html>
