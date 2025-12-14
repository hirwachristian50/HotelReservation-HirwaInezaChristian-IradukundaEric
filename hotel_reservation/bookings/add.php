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

// Fetch available rooms
try {
    $stmt = $conn->prepare("SELECT id, room_number FROM rooms WHERE status = 'Available'");
    $stmt->execute();
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error loading rooms.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $room_id   = $_POST["room_id"];
    $check_in  = $_POST["check_in"];
    $check_out = $_POST["check_out"];
    $user_id   = $_SESSION["user_id"];

    if (empty($room_id) || empty($check_in) || empty($check_out)) {
        $error = "All fields are required.";
    } elseif ($check_in >= $check_out) {
        $error = "Check-out date must be after check-in date.";
    } else {

        try {
            // Insert booking
            $stmt = $conn->prepare(
                "INSERT INTO bookings (user_id, room_id, check_in, check_out)
                 VALUES (:user_id, :room_id, :check_in, :check_out)"
            );

            $stmt->bindValue(":user_id", $user_id);
            $stmt->bindValue(":room_id", $room_id);
            $stmt->bindValue(":check_in", $check_in);
            $stmt->bindValue(":check_out", $check_out);

            $stmt->execute();

            $success = "Booking created successfully!";

        } catch (PDOException $e) {
            $error = "Failed to create booking.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Booking</title>
    <link rel="stylesheet" href="../assets/css/style.css">

</head>
<body>
    <div class="container"></div>

<h2>Create Booking</h2>

<?php if ($error): ?>
    <p style="color:red;"><?php echo $error; ?></p>
<?php endif; ?>

<?php if ($success): ?>
    <p style="color:green;"><?php echo $success; ?></p>
<?php endif; ?>

<form method="post">
    <label>Room</label><br>
    <select name="room_id">
        <option value="">-- Select Room --</option>
        <?php foreach ($rooms as $room): ?>
            <option value="<?php echo $room["id"]; ?>">
                Room <?php echo $room["room_number"]; ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Check-in Date</label><br>
    <input type="date" name="check_in"><br><br>

    <label>Check-out Date</label><br>
    <input type="date" name="check_out"><br><br>

    <button type="submit">Create Booking</button>
</form>

<br>
<a href="../index.php">Back to Dashboard</a>

</body>
</html>
