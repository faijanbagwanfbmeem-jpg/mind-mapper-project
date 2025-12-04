<?php
require_once "../../../app/db.php";

$query = $mysqli->query("
    SELECT * FROM student_info
    ORDER BY (iq_percent + eq_percent) DESC
    LIMIT 10
");
?>
<!DOCTYPE html>
<html>
<head>
<title>Top Performers</title>
<style>
body { font-family: Arial; padding:20px; }
h2 { text-align:center; }
table { width:100%; border-collapse:collapse; }
th, td { border:1px solid black; padding:10px; }
.print-btn { padding:10px; color:white; background:black; cursor:pointer; }
@media print { .print-btn { display:none; } }
</style>
</head>
<body>

<button class="print-btn" onclick="window.print()">Print Report</button>

<h2>Top Performing Students</h2>

<table>
<tr>
    <th>ID</th>
    <th>Student Name</th>
    <th>IQ %</th>
    <th>EQ %</th>
    <th>Combined Score</th>
</tr>

<?php while($row = $query->fetch_assoc()): ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['full_name'] ?></td>
    <td><?= $row['iq_percent'] ?>%</td>
    <td><?= $row['eq_percent'] ?>%</td>
    <td><?= $row['iq_percent'] + $row['eq_percent'] ?></td>
</tr>
<?php endwhile; ?>
</table>

</body>
</html>
