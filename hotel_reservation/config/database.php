<?php

$host = "localhost";
$dbname = "hotel_reservation";
$username = "root";
$password = ""; // default for XAMPP

try {
    // Create PDO connection
    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $username,
        $password
    );

    // Set error mode to Exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Clean error handling (no raw errors shown)
    die("Database connection failed. Please try again later.");
}