<?php
require_once "../../../app/db.php";

// Fetch today's records
$today = date("Y-m-d");
$query = $mysqli->query("
    SELECT student_info.*, 
    (SELECT score FROM exam_results WHERE student_id = student_info.id ORDER BY id DESC LIMIT 1) AS last_score,
    (SELECT exam_type FROM exam_results WHERE student_id = student_info.id ORDER BY id DESC LIMIT 1) AS exam_type
    FROM student_info
    WHERE DATE(created) = '$today'
    ORDER BY created DESC
");
?>
<!DOCTYPE html>
<html>
<head>
<title>Daily Student Report</title>
<style>
body { font-family: Arial; padding: 20px; }
h2 { text-align:center; text-transform:uppercase; }
table { width:100%; border-collapse:collapse; margin-top:20px; }
table, th, td { border:1px solid black; }
th, td { padding:10px; text-align:left; }
.print-btn {
    padding:10px 20px;
    background:black;
    color:white;
    border:none;
    margin-bottom:20px;
    cursor:pointer;
}
@media print {
    .print-btn { display:none; }
}
</style>
</head>
<body>

<button class="print-btn" onclick="window.print()">Print Report</button>

<h2>Daily Student Report (<?= date("d M Y") ?>)</h2>

<table>
<tr>
    <th>ID</th>
    <th>Student Name</th>
    <th>IQ %</th>
    <th>EQ %</th>
    <th>Latest Score</th>
    <th>Exam Type</th>
    <th>Counselor</th>
</tr>

<?php while($row = $query->fetch_assoc()): ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['full_name'] ?></td>
    <td><?= $row['iq_percent'] ?>%</td>
    <td><?= $row['eq_percent'] ?>%</td>
    <td><?= $row['last_score'] ?: "-" ?></td>
    <td><?= strtoupper($row['exam_type'] ?: "-") ?></td>
    <td><?= $row['counselor_sent'] ?></td>
</tr>
<?php endwhile; ?>
</table>

</body>
</html>
