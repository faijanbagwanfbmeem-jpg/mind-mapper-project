<?php
require_once "../../app/db.php";
include "inc/header.php";

date_default_timezone_set("Asia/Kolkata");

// Detect report range
$range = $_GET['range'] ?? 'daily';

if ($range == 'daily') {
    $start_date = date("Y-m-d");
} elseif ($range == 'weekly') {
    $start_date = date("Y-m-d", strtotime("-6 days"));
} else {
    $start_date = date("Y-m-d", strtotime("-29 days"));
}

$end_date = date("Y-m-d");

// Fetch combined IQ + EQ results
$sql = "
SELECT 
    si.id AS sid,
    si.full_name,
    si.counselor_sent,
    si.created,
    ROUND(AVG(CASE WHEN er.exam_type='IQ' THEN er.score END),2) AS avg_iq,
    ROUND(AVG(CASE WHEN er.exam_type='EQ' THEN er.score END),2) AS avg_eq
FROM student_info si
LEFT JOIN exam_results er 
    ON er.student_id = si.user_id 
    AND DATE(er.created) BETWEEN ? AND ?
GROUP BY si.id
HAVING (avg_iq IS NOT NULL OR avg_eq IS NOT NULL)
ORDER BY si.created DESC
";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ss", $start_date, $end_date);
$stmt->execute();
$res = $stmt->get_result();

$MAX_TOTAL = 20;
?>

<style>
.report-box { background:white; padding:20px; border-radius:10px; border:1px solid #ccc; }
.selector-box { margin-bottom:20px; display:flex; gap:10px; }
.print-btn {
    background:black; color:white; padding:10px 20px; border:0; border-radius:5px;
    cursor:pointer; text-align:center; display:block; margin:0 auto 20px auto;
}
table { width:100%; border-collapse:collapse; margin-top:10px; }
th, td { border:1px solid #ddd; padding:10px; text-align:center; }
th { background:#f3f3f3; }
td.name { text-align:left; }
@media print { 
    .selector-box, .print-btn { display:none; }
}
</style>

<h2>Combined Report (IQ + EQ)</h2>

<!-- Range selector -->
<div class="selector-box">
    <form method="get">
        <select name="range" onchange="this.form.submit()">
            <option value="daily" <?= $range=='daily'?'selected':'' ?>>Daily</option>
            <option value="weekly" <?= $range=='weekly'?'selected':'' ?>>Weekly</option>
            <option value="monthly" <?= $range=='monthly'?'selected':'' ?>>Monthly</option>
        </select>
    </form>
</div>

<!-- Print button -->
<button class="print-btn" onclick="window.print()">Print Report</button>

<div class="report-box">
    <h3 style="text-align:center;">
        Showing: <?= ucfirst($range) ?> Report (<?= $start_date ?> to <?= $end_date ?>)
    </h3>

    <?php if ($res->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th style="text-align:left;">Student Name</th>
                <th>IQ Score</th>
                <th>EQ Score</th>
                <th>Combined %</th>
                <th>Counselor Status</th>
                <th>Registration Date</th>
            </tr>
        </thead>
        <tbody>
        <?php while($r = $res->fetch_assoc()): 
            $iq = $r['avg_iq'] ?? 0;
            $eq = $r['avg_eq'] ?? 0;
            $combined = round((($iq + $eq) / $MAX_TOTAL) * 100);
        ?>
        <tr>
            <td><?= $r['sid'] ?></td>
            <td class="name"><?= htmlspecialchars($r['full_name']) ?></td>
            <td><?= $iq ?></td>
            <td><?= $eq ?></td>
            <td><?= $combined ?>%</td>
            <td><?= htmlspecialchars($r['counselor_sent'] ?: 'Pending') ?></td>
            <td><?= $r['created'] ?></td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p style="text-align:center; padding:20px;">No records found for this period.</p>
    <?php endif; ?>
</div>

<?php include "inc/footer.php"; ?>
