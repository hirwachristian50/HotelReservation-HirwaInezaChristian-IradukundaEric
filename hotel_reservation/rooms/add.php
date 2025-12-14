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

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $room_number = trim($_POST["room_number"]);
    $type = trim($_POST["type"]);
    $price = trim($_POST["price"]);

    // Validation
    if (empty($room_number) || empty($type) || empty($price)) {
        $error = "All fields are required.";
    } elseif (!is_numeric($price)) {
        $error = "Price must be a number.";
    } else {

        try {
            $stmt = $conn->prepare(
                "INSERT INTO rooms (room_number, type, price)
                 VALUES (:room_number, :type, :price)"
            );

            $stmt->bindValue(":room_number", $room_number);
            $stmt->bindValue(":type", $type);
            $stmt->bindValue(":price", $price);

            $stmt->execute();

            $success = "Room added successfully!";
        } catch (PDOException $e) {
            $error = "Room number already exists or database error.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Room</title>
     <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <body>
<div class="container"></div>


<h2>Add Room</h2>

<?php if ($error): ?>
    <p class="error"><?php echo $error; ?></p>

<?php endif; ?>

<?php if ($success): ?>
    <p class="error"><?php echo $error; ?></p>

<?php endif; ?>

<form method="post">
    <label>Room Number</label><br>
    <input type="text" name="room_number"><br><br>

    <label>Room Type</label><br>
    <input type="text" name="type" placeholder="Single / Double"><br><br>

    <label>Price per Night</label><br>
    <input type="text" name="price"><br><br>

    <button type="submit">Add Room</button>
</form>

<br>
<a href="../index.php">Back to Dashboard</a>

</body>
</html>
