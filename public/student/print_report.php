<?php
require_once "../../app/db.php";
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

$student_id = $_SESSION['student_id'];
$id = $_GET['id'] ?? 0;

$stmt = $mysqli->prepare("
    SELECT r.*, s.full_name 
    FROM exam_results r 
    JOIN student_info s ON r.student_id = s.user_id
    WHERE r.id=? AND r.student_id=? LIMIT 1
");
$stmt->bind_param("ii", $id, $student_id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    die("Report not found.");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Scorecard</title>
<style>
body { font-family: Arial; padding: 20px; }
.box { border: 2px solid #000; padding: 20px; width: 600px; margin: auto; }
h2 { text-align:center; }
.btn { padding: 10px; background:black; color:white; text-decoration:none; }
</style>
</head>
<body>

<div class="box">
<h2>MindMapper Score Report</h2>

<p><strong>Name:</strong> <?= $data['full_name'] ?></p>
<p><strong>Exam Type:</strong> <?= $data['exam_type'] ?></p>
<p><strong>Score:</strong> <?= $data['score'] ?> / <?= $data['max_score'] ?></p>
<p><strong>Date:</strong> <?= $data['created'] ?></p>

<hr>

<p style="text-align:center;">
<button onclick="window.print()" class="btn">Print</button>
</p>
</div>

</body>
</html>
