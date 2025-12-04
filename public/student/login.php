<?php
require_once "../../app/db.php";
session_start();
include "../inc/header.php";

$err = "";

// --------------------------------------
// LOGIN PROCESS
// --------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ? AND role='student' LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res && $res->num_rows === 1) {

        $user = $res->fetch_assoc();

        if (password_verify($password, $user["password"])) {

            // Store Login Session
            $_SESSION["student_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];

            header("Location: dashboard.php");
            exit;

        } else {
            $err = "Incorrect password.";
        }

    } else {
        $err = "Student not found.";
    }
}
?>

<style>
.page-wrapper{
    min-height:70vh;
    padding-top:50px;
}

.login-box{
    background:white;
    width:90%;
    max-width:500px;
    margin:0 auto;
    padding:30px;
    padding-bottom:40px;
    border-radius:12px;
    box-shadow:0 8px 25px rgba(0,0,0,0.1);
}

h2{
    text-align:center;
    margin-bottom:25px;
    font-size:28px;
    color:#0B132B;
}

.input-field{
    width:100%;
    padding:12px;
    margin-bottom:15px;
    border-radius:8px;
    border:1px solid #bbb;
}

.login-btn{
    width:100%;
    padding:12px;
    background:#0b66ff;
    color:white;
    border:none;
    font-size:16px;
    border-radius:8px;
    cursor:pointer;
    font-weight:600;
}

.login-btn:hover{
    opacity:0.9;
}

.err{
    background:#ffe0e0;
    color:#cc0000;
    padding:12px;
    text-align:center;
    border-radius:6px;
    font-weight:600;
    margin-bottom:10px;
}
</style>

<div class="page-wrapper">

    <div class="login-box">
        <h2>Student Login</h2>

        <?php if ($err): ?>
        <p class="err"><?= $err ?></p>
        <?php endif; ?>

        <form method="post">
            <input type="text" name="username" class="input-field" placeholder="Username" required>
            <input type="password" name="password" class="input-field" placeholder="Password" required>

            <button type="submit" class="login-btn">Login</button>
        </form>

        <p style="text-align:center; margin-top:10px;">
            Not registered? <a href="register.php">Create Account</a>
        </p>
    </div>

</div>

<?php include "../inc/footer.php"; ?>
