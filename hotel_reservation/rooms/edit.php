<?php
session_start();

// Protect page
if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/database.php";

$error = "";
$success = "";

// Check if ID exists
if (!isset($_GET["id"])) {
    header("Location: list.php");
    exit;
}

$id = $_GET["id"];

// Fetch existing room
try {
    $stmt = $conn->prepare("SELECT * FROM rooms WHERE id = :id");
    $stmt->bindValue(":id", $id);
    $stmt->execute();
    $room = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$room) {
        header("Location: list.php");
        exit;
    }
} catch (PDOException $e) {
    die("Error loading room.");
}

// Update room
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $room_number = trim($_POST["room_number"]);
    $type = trim($_POST["type"]);
    $price = trim($_POST["price"]);
    $status = trim($_POST["status"]);

    if (empty($room_number) || empty($type) || empty($price) || empty($status)) {
        $error = "All fields are required.";
    } elseif (!is_numeric($price)) {
        $error = "Price must be numeric.";
    } else {

        try {
            $stmt = $conn->prepare(
                "UPDATE rooms 
                 SET room_number = :room_number,
                     type = :type,
                     price = :price,
                     status = :status
                 WHERE id = :id"
            );

            $stmt->bindValue(":room_number", $room_number);
            $stmt->bindValue(":type", $type);
            $stmt->bindValue(":price", $price);
            $stmt->bindValue(":status", $status);
            $stmt->bindValue(":id", $id);

            $stmt->execute();

            $success = "Room updated successfully!";

        } catch (PDOException $e) {
            $error = "Update failed. Room number may already exist.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Room</title>
    <link rel="stylesheet" href="../assets/css/style.css">

</head>
<body>

<div class="container"></div>


<h2>Edit Room</h2>

<?php if ($error): ?>
    <p style="color:red;"><?php echo $error; ?></p>
<?php endif; ?>

<?php if ($success): ?>
    <p style="color:green;"><?php echo $success; ?></p>
<?php endif; ?>

<form method="post">
    <label>Room Number</label><br>
    <input type="text" name="room_number"
           value="<?php echo $room['room_number']; ?>"><br><br>

    <label>Room Type</label><br>
    <input type="text" name="type"
           value="<?php echo $room['type']; ?>"><br><br>

    <label>Price</label><br>
    <input type="text" name="price"
           value="<?php echo $room['price']; ?>"><br><br>

    <label>Status</label><br>
    <select name="status">
        <option value="Available"
            <?php if ($room['status'] === 'Available') echo 'selected'; ?>>
            Available
        </option>
        <option value="Booked"
            <?php if ($room['status'] === 'Booked') echo 'selected'; ?>>
            Booked
        </option>
    </select><br><br>

    <button type="submit">Update Room</button>
</form>

<br>
<a href="list.php">Back to Rooms</a>

</body>
</html>
