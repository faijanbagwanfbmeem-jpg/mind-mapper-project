<?php
require_once "../../app/db.php";
session_start();
include "../inc/header.php";

$message = "";

// -----------------------------------------
// PROCESS STUDENT REGISTRATION
// -----------------------------------------
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $full_name = trim($_POST['full_name']);
    $username  = trim($_POST['username']);
    $email     = trim($_POST['email']);
    $age       = intval($_POST['age']);   // AGE (14–17)
    $school    = trim($_POST['school_name']); // NEW SCHOOL NAME
    $password  = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // AGE VALIDATION
    if ($age < 14 || $age > 17) {
        $message = "<div class='error-msg'>Age must be between 14 and 17.</div>";
    } else {

        // Check username OR email already exists
        $check = $mysqli->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $check->bind_param("ss", $username, $email);
        $check->execute();
        $exists = $check->get_result();

        if ($exists->num_rows > 0) {
            $message = "<div class='error-msg'>Username or Email already exists.</div>";
        } else {

            // Insert into users
            $stmt = $mysqli->prepare("
                INSERT INTO users (username, password, email, role) 
                VALUES (?, ?, ?, 'student')
            ");
            $stmt->bind_param("sss", $username, $password, $email);

            if ($stmt->execute()) {
                $new_user_id = $stmt->insert_id;

                // Insert student_info (INCLUDING SCHOOL NAME)
                $info = $mysqli->prepare("
                    INSERT INTO student_info (user_id, full_name, school_name, age, counselor_sent, created)
                    VALUES (?, ?, ?, ?, 'No', NOW())
                ");
                $info->bind_param("issi", $new_user_id, $full_name, $school, $age);
                $info->execute();

                $message = "
                    <div class='success-msg'>
                        Registration successful! 
                        <a href='login.php'>Login Now</a>
                    </div>
                ";
            } else {
                $message = "<div class='error-msg'>Registration Error. Try again.</div>";
            }
        }
    }
}
?>

<style>
.page-wrapper{
    min-height:70vh;
    padding-top:40px;
}
.form-box{
    background:white;
    width:90%;
    max-width:1100px;
    margin:0 auto;
    padding:30px;
    padding-bottom:40px;
    border-radius:12px;
    box-shadow:0 8px 25px rgba(0,0,0,0.08);
}
h2{
    text-align:center;
    margin-bottom:25px;
    font-size:28px;
    color:#0B132B;
}
.form-row{
    display:flex;
    flex-wrap:wrap;
    justify-content:space-between;
    gap:15px;
}
.form-row input, .form-row select{
    flex:1;
    min-width:48%;
    padding:12px;
    border:1px solid #ccc;
    border-radius:8px;
}
.small-input {
    width:48%;
    padding:12px;
    border:1px solid #ccc;
    border-radius:8px;
}
.reg-btn{
    margin-top:20px;
    background:#0b66ff;
    color:white;
    padding:12px 25px;
    border:none;
    border-radius:8px;
    font-weight:600;
    cursor:pointer;
}
.reg-btn:hover{
    opacity:0.9;
}
.success-msg, .error-msg{
    margin-bottom:15px;
    padding:12px;
    border-radius:6px;
    text-align:center;
    font-weight:600;
}
.success-msg{
    background:#d4ffd9;
    color:#0a7a15;
}
.error-msg{
    background:#ffe0e0;
    color:#cc0000;
}
</style>

<div class="page-wrapper">

    <?= $message ?>

    <div class="form-box">
        <h2>Student Registration</h2>

        <form method="POST">
            <div class="form-row">

                <input type="text" name="full_name" placeholder="Full Name" required>

                <!-- NEW SCHOOL NAME FIELD -->
                <input type="text" name="school_name" placeholder="School Name" required>

                <input type="text" name="username" placeholder="Username" required>

                <input type="email" name="email" placeholder="Email" required>

                <!-- AGE DROPDOWN -->
                <select name="age" required>
                    <option value="">Select Age (14–17)</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                </select>

                <!-- Small Password field -->
                <input type="password" name="password" placeholder="Password" class="small-input" required>
            </div>

            <div style="text-align:center;">
                <button class="reg-btn" type="submit">Register</button>
            </div>
        </form>

    </div>

</div>

<?php include "../inc/footer.php"; ?>
