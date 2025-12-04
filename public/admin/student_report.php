<?php
require_once "../../app/db.php";

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) die("Invalid student");

// fetch student_info + user info
$stmt = $mysqli->prepare("
    SELECT si.*, u.username, u.email 
    FROM student_info si
    LEFT JOIN users u ON u.id = si.user_id
    WHERE si.id = ?
    LIMIT 1
");
$stmt->bind_param("i", $id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

if (!$student) die("Student not found");

// Latest IQ result
$iq = $mysqli->query("
    SELECT * FROM exam_results
    WHERE student_id = {$student['user_id']} AND exam_type='IQ'
    ORDER BY id DESC LIMIT 1
")->fetch_assoc();

// Latest EQ result
$eq = $mysqli->query("
    SELECT * FROM exam_results
    WHERE student_id = {$student['user_id']} AND exam_type='EQ'
    ORDER BY id DESC LIMIT 1
")->fetch_assoc();

$max = 10;
$iq_percent = $iq ? round(($iq['score'] / $max) * 100) : 0;
$eq_percent = $eq ? round(($eq['score'] / $max) * 100) : 0;

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Student Report - <?= htmlspecialchars($student['full_name']) ?></title>
<style>
body { font-family: Arial; margin:20px; }
.header { text-align:center; margin-bottom:20px; }
.box { border:1px solid #000; padding:16px; margin-bottom:12px; border-radius:6px; }
.kv { margin:6px 0; }
.no-print { text-align:center; margin-bottom:20px; }
.no-print button {
    padding:10px 20px; background:black; color:white; border:none;
    border-radius:5px; cursor:pointer;
}
.no-print a { margin-left:20px; }

@media print {
  .no-print { display:none; }
}
</style>
</head>
<body>

<div class="no-print">
    <button onclick="window.print()">Print / Save PDF</button>
    <a href="students.php">Back</a>
</div>

<div class="header">
    <h1>MindMapper – Student Report</h1>
    <h2><?= htmlspecialchars($student['full_name']) ?></h2>
</div>

<div class="box">
    <div class="kv"><strong>ID:</strong> <?= $student['id'] ?></div>
    <div class="kv"><strong>Username:</strong> <?= htmlspecialchars($student['username']) ?></div>
    <div class="kv"><strong>Email:</strong> <?= htmlspecialchars($student['email']) ?></div>
    <div class="kv"><strong>Registered:</strong> <?= $student['created'] ?></div>
    <div class="kv"><strong>Counselor Status:</strong> <?= $student['counselor_sent'] ?></div>
</div>

<div class="box">
    <h3>IQ Summary</h3>
    <div class="kv"><strong>IQ %:</strong> <?= $iq_percent ?>%</div>
    <?php if ($iq): ?>
        <div class="kv"><strong>Last IQ Score:</strong> <?= $iq['score'] ?> / 10</div>
        <div class="kv"><strong>Date:</strong> <?= $iq['created'] ?></div>
    <?php else: ?>
        <div class="kv">No IQ exam available.</div>
    <?php endif; ?>
</div>

<div class="box">
    <h3>EQ Summary</h3>
    <div class="kv"><strong>EQ %:</strong> <?= $eq_percent ?>%</div>
    <?php if ($eq): ?>
        <div class="kv"><strong>Last EQ Score:</strong> <?= $eq['score'] ?> / 10</div>
        <div class="kv"><strong>Date:</strong> <?= $eq['created'] ?></div>
    <?php else: ?>
        <div class="kv">No EQ exam available.</div>
    <?php endif; ?>
</div>

</body>
</html>
