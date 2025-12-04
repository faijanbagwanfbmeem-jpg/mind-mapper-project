<?php
require_once "../../../app/db.php";

$query = $mysqli->query("
    SELECT student_info.*, 
    (SELECT score FROM exam_results WHERE student_id = student_info.id ORDER BY id DESC LIMIT 1) AS last_score,
    (SELECT exam_type FROM exam_results WHERE student_id = student_info.id ORDER BY id DESC LIMIT 1) AS exam_type
    FROM student_info
    WHERE created >= DATE(NOW()) - INTERVAL 30 DAY
    ORDER BY created DESC
");
?>
<!DOCTYPE html>
<html>
<head>
<title>Monthly Report</title>
<style>
body { font-family: Arial; padding: 20px; }
h2 { text-align:center; }
table { width:100%; border-collapse: collapse; }
th, td { padding:10px; border:1px solid black; }
.print-btn { padding:10px; background:black; color:white; cursor:pointer; }
@media print { .print-btn { display:none; } }
</style>
</head>
<body>

<button class="print-btn" onclick="window.print()">Print Report</button>

<h2>Monthly Report (Last 30 Days)</h2>

<table>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>IQ %</th>
    <th>EQ %</th>
    <th>Score</th>
    <th>Exam</th>
    <th>Counselor</th>
</tr>

<?php while($row = $query->fetch_assoc()): ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['full_name'] ?></td>
    <td><?= $row['iq_percent'] ?>%</td>
    <td><?= $row['eq_percent'] ?>%</td>
    <td><?= $row['last_score'] ?></td>
    <td><?= $row['exam_type'] ? strtoupper($row['exam_type']) : "-" ?></td>
    <td><?= $row['counselor_sent'] ?></td>
</tr>
<?php endwhile; ?>
</table>

</body>
</html>
