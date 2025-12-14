<?php
session_start();

// Protect page
if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/database.php";

// Check if ID is provided
if (!isset($_GET["id"])) {
    header("Location: list.php");
    exit;
}

$id = $_GET["id"];

try {
    $stmt = $conn->prepare("DELETE FROM rooms WHERE id = :id");
    $stmt->bindValue(":id", $id);
    $stmt->execute();

} catch (PDOException $e) {
    // You can log error here if needed
}

// Redirect back to rooms list
header("Location: list.php");
exit;
