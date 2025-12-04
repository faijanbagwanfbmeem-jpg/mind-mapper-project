<?php
require_once "../../app/db.php";
session_start();

// Check login
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

$student_id = $_SESSION['student_id'];

// Fetch student info (including school_name)
$stu = $mysqli->prepare("
    SELECT si.*, u.username, u.email
    FROM student_info si
    JOIN users u ON si.user_id = u.id
    WHERE si.user_id = ?
");
$stu->bind_param("i", $student_id);
$stu->execute();
$student = $stu->get_result()->fetch_assoc();
if (!$student) { die("Student data not found."); }

// Fetch latest IQ
$iq = $mysqli->query("
    SELECT * FROM exam_results 
    WHERE student_id = $student_id AND exam_type='IQ'
    ORDER BY id DESC LIMIT 1
")->fetch_assoc();

// Fetch latest EQ
$eq = $mysqli->query("
    SELECT * FROM exam_results 
    WHERE student_id = $student_id AND exam_type='EQ'
    ORDER BY id DESC LIMIT 1
")->fetch_assoc();

// Safe defaults
$iq_score = $iq ? $iq['score'] : 0;
$eq_score = $eq ? $eq['score'] : 0;

// Convert EQ from 50 → 10 scale
if ($eq && $eq['max_score'] == 50) {
    $eq_score = round(($eq['score'] / 50) * 10);
}

$combined_percentage = round((($iq_score + $eq_score) / 20) * 100);
?>
<!DOCTYPE html>
<html>
<head>
<title>Combined Score Report</title>

<style>
body {
    font-family: Arial;
    background: #f5f5f5;
    padding: 30px;
}

.report-box {
    width: 750px;
    background: white;
    margin: auto;
    padding: 25px;
    border-radius: 10px;
    border: 2px solid #000;
}

h2, h3 {
    text-align: center;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}
.table th, .table td {
    border: 1px solid black;
    padding: 10px;
    text-align: center;
}

.print-btn {
    display: block;
    margin: 20px auto;
    background: black;
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 7px;
    cursor: pointer;
    font-size: 16px;
}

@media print {
    .print-btn { display: none; }
    body { background: white; }
}
</style>

</head>
<body>

<div class="report-box">
    <h2>MindMapper - Combined Score Report</h2>

    <table class="table">
        <tr>
            <th>Student Name</th>
            <td><?= $student['full_name'] ?></td>
        </tr>
        <tr>
            <th>School Name</th>
            <td><?= $student['school_name'] ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?= $student['email'] ?></td>
        </tr>
        <tr>
            <th>Registration Date</th>
            <td><?= $student['created'] ?></td>
        </tr>
    </table>

    <h3>Test Performance Summary</h3>

    <table class="table">
        <tr>
            <th>IQ Score</th>
            <td><?= $iq_score ?> / 10</td>
        </tr>
        <tr>
            <th>EQ Score</th>
            <td><?= $eq_score ?> / 10</td>
        </tr>
        <tr>
            <th>Combined Percentage</th>
            <td><strong><?= $combined_percentage ?>%</strong></td>
        </tr>
    </table>

    <button class="print-btn" onclick="window.print()">Print Report</button>

</div>

</body>
</html>
