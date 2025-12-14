<?php
session_start();

// Protect page
if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/database.php";

// Check booking ID
if (!isset($_GET["id"])) {
    header("Location: list.php");
    exit;
}

$booking_id = $_GET["id"];

try {
    // Start transaction
    $conn->beginTransaction();

    // Get room_id for this booking
    $stmt = $conn->prepare(
        "SELECT room_id FROM bookings WHERE id = :id AND status = 'Pending'"
    );
    $stmt->bindValue(":id", $booking_id);
    $stmt->execute();
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$booking) {
        $conn->rollBack();
        header("Location: list.php");
        exit;
    }

    $room_id = $booking["room_id"];

    // Update booking status
    $stmt = $conn->prepare(
        "UPDATE bookings SET status = 'Approved' WHERE id = :id"
    );
    $stmt->bindValue(":id", $booking_id);
    $stmt->execute();

    // Update room status
    $stmt = $conn->prepare(
        "UPDATE rooms SET status = 'Booked' WHERE id = :room_id"
    );
    $stmt->bindValue(":room_id", $room_id);
    $stmt->execute();

    // Commit transaction
    $conn->commit();

} catch (PDOException $e) {
    $conn->rollBack();
}

// Redirect back to bookings list
header("Location: list.php");
exit;
