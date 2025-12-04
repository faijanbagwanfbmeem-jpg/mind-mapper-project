<?php
require_once "../../app/db.php";
include "inc/header.php";

$msg = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST['counselor_name'];
    $email = $_POST['counselor_email'];
    $mobile = $_POST['mobile'];

    $stmt = $mysqli->prepare("
        INSERT INTO counselor_master (counselor_name, counselor_email, mobile)
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param("sss", $name, $email, $mobile);

    if ($stmt->execute()) {
        $msg = "<p style='color:green;'>Counselor Added!</p>";
    }
}
?>

<h2>Add Counselor</h2>
<?= $msg ?>

<form method="POST">
    <input type="text" name="counselor_name" placeholder="Counselor Name" required>
    <input type="email" name="counselor_email" placeholder="Email" required>
    <input type="text" name="mobile" placeholder="Mobile Number">
    <button type="submit">Save</button>
</form>

<a href="counselor_list.php">View All</a>
