<?php
require_once "../../../app/db.php";

// Last 7 days
$query = $mysqli->query("
    SELECT student_info.*, 
    (SELECT score FROM exam_results WHERE student_id = student_info.id ORDER BY id DESC LIMIT 1) AS last_score,
    (SELECT exam_type FROM exam_results WHERE student_id = student_info.id ORDER BY id DESC LIMIT 1) AS exam_type
    FROM student_info
    WHERE created >= DATE(NOW()) - INTERVAL 7 DAY
    ORDER BY created DESC
");
?>
<!DOCTYPE html>
<html>
<head>
<title>Weekly Student Report</title>
<style>
body { font-family: Arial; padding: 20px; }
h2 { text-align:center; text-transform:uppercase; }
table { width:100%; border-collapse:collapse; margin-top:20px; }
th, td { border:1px solid black; padding:10px; }
.print-btn { padding:10px 20px; background:black; color:white; cursor:pointer; }
@media print { .print-btn { display:none; } }
</style>
</head>
<body>

<button class="print-btn" onclick="window.print()">Print Report</button>

<h2>Weekly Student Report (Last 7 Days)</h2>

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
