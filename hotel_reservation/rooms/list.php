<?php
session_start();

// Protect page
if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/database.php";

try {
    $stmt = $conn->prepare("SELECT * FROM rooms ORDER BY id DESC");
    $stmt->execute();
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching rooms.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Rooms List</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="container"> </div>

<h2>Rooms</h2>

<a href="add.php">Add New Room</a> |
<a href="../index.php">Dashboard</a>

<br><br>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Room Number</th>
        <th>Type</th>
        <th>Price</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>

    <?php if ($rooms): ?>
        <?php foreach ($rooms as $room): ?>
            <tr>
                <td><?php echo $room["id"]; ?></td>
                <td><?php echo $room["room_number"]; ?></td>
                <td><?php echo $room["type"]; ?></td>
                <td><?php echo $room["price"]; ?></td>
                <td><?php echo $room["status"]; ?></td>
                <td>
                   <a class="btn btn-edit" href="edit.php?id=<?php echo $room['id']; ?>">Edit</a>
<a class="btn btn-delete" href="delete.php?id=<?php echo $room['id']; ?>" onclick="return confirm('Are you sure you want to delete this room?');">Delete</a>


                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="6">No rooms found.</td>
        </tr>
    <?php endif; ?>

</table>

</body>
</html>
