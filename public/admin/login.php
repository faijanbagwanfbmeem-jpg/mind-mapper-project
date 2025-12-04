<?php
session_start();
require_once "../../app/db.php";

// Admin login handling
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username=? AND role='admin' LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin["password"])) {
            $_SESSION["admin_id"] = $admin["id"];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "Admin user not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Login - MindMapper</title>
<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f5f7fa;
}

/* Header */
.top-header {
    background: #007bff;
    padding: 18px;
    text-align: center;
}

.top-header img {
    height: 55px;
}

/* Login Card */
.login-container {
    max-width: 450px;
    margin: 60px auto;
    background: #ffffff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

h2 {
    text-align: center;
    margin-bottom: 25px;
}

/* Input fields */
input {
    width: 100%;
    padding: 12px;
    margin-top: 12px;
    border: 1px solid #ccc;
    border-radius: 8px;
}

/* Login Button */
.login-btn {
    width: 100%;
    padding: 12px;
    margin-top: 20px;
    background: #000;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
}

.login-btn:hover {
    opacity: 0.8;
}

/* Error message */
.error {
    background: #ffd6d6;
    padding: 10px;
    border-left: 4px solid red;
    margin-bottom: 10px;
    color: #990000;
    font-weight: bold;
}

/* Footer */
.footer {
    margin-top: 80px;
    padding: 10px;
    background: #eef2f7;
    text-align: center;
    color: #444;
    position: relative;
}
</style>
</head>

<body>

<!-- HEADER -->
<div class="top-header">
    <img src="../assets/logo.png" alt="MindMapper">
</div>

<!-- LOGIN CARD -->
<div class="login-container">
    <h2>Admin Login</h2>

    <?php if ($error != ""): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Admin Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button class="login-btn" type="submit">Login</button>
    </form>
</div>

<!-- FOOTER -->
<div class="footer">
    © 2025 MindMapper - All Rights Reserved.
</div>

</body>
</html>
