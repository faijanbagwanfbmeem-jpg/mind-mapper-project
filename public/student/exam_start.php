<?php
require_once "../../app/db.php";
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

$type = $_GET['type'] ?? '';
if (!in_array($type, ["IQ", "EQ"])) {
    die("Invalid exam type");
}

include "../inc/header.php";
?>

<h2>Start <?= $type ?> Test</h2>

<form method="post" action="take_exam.php">
    <input type="hidden" name="exam_type" value="<?= $type ?>">
    <button class="btn">Begin <?= $type ?> Test</button>
</form>

<?php include "../inc/footer.php"; ?>
