<?php
session_start();
require_once "../config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (empty($username) || empty($password)) {
        $error = "All fields are required.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindValue(":username", $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user["password"])) {
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["username"] = $user["username"];
                header("Location: ../index.php");
                exit;
            } else {
                $error = "Invalid username or password.";
            }
        } catch (PDOException $e) {
            $error = "Database error.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Hotel Reservation</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="login-wrapper">
    <div class="login-card">
        <h2>🏨 Hotel Reservation</h2>
        <p>Sign in to your account</p>

        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="post">
            <label>Username</label>
            <input type="text" name="username" placeholder="Enter username">

            <label>Password</label>
            <input type="password" name="password" placeholder="Enter password">

            <button type="submit">Login</button>
        </form>
    </div>
</div>

</body>
</html>
