<?php
require_once "../../app/db.php";
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

$student_id = $_SESSION['student_id'];
$id = $_GET['id'] ?? 0;

// Fetch the single result first
$stmt = $mysqli->prepare("SELECT * FROM exam_results WHERE id=? AND student_id=? LIMIT 1");
$stmt->bind_param("ii", $id, $student_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if (!$result) {
    die("Result not found");
}

// -------------------------------------------------
// FETCH STUDENT INFORMATION (IMPORTANT FIX)
// -------------------------------------------------
$stu = $mysqli->prepare("SELECT * FROM student_info WHERE user_id = ?");
$stu->bind_param("i", $student_id);
$stu->execute();
$info = $stu->get_result()->fetch_assoc();

// If not found → create it
if (!$info) {
    $create = $mysqli->prepare("
        INSERT INTO student_info (user_id, full_name, counselor_sent, created)
        VALUES (?, ?, 'No', NOW())
    ");
    $create->bind_param("is", $student_id, $_SESSION['username']);
    $create->execute();

    // Fetch updated student_info
    $stu->execute();
    $info = $stu->get_result()->fetch_assoc();
}

// -------------------------------------------------
// FETCH LATEST IQ + EQ RESULTS FOR COMBINED REPORT
// -------------------------------------------------

// IQ result
$iq = $mysqli->prepare("
    SELECT * FROM exam_results 
    WHERE student_id=? AND exam_type='IQ' 
    ORDER BY created DESC LIMIT 1
");
$iq->bind_param("i", $student_id);
$iq->execute();
$iq_result = $iq->get_result()->fetch_assoc();

// EQ result
$eq = $mysqli->prepare("
    SELECT * FROM exam_results 
    WHERE student_id=? AND exam_type='EQ' 
    ORDER BY created DESC LIMIT 1
");
$eq->bind_param("i", $student_id);
$eq->execute();
$eq_result = $eq->get_result()->fetch_assoc();

include "../inc/header.php";
?>

<h2>Combined IQ + EQ Report</h2>

<div class="card" style="background:white; padding:15px; border-radius:8px; margin-bottom:20px;">
    <p><strong>Student Name:</strong> <?= $info['full_name'] ?></p>
    <p><strong>Date Registered:</strong> <?= $info['created'] ?></p>
</div>

<!-- IQ RESULT -->
<div class="card" style="background:white; padding:15px; border-radius:8px; margin-bottom:20px;">
    <h3>IQ Test</h3>

    <?php if ($iq_result): ?>
        <p><strong>Score:</strong> <?= $iq_result['score'] ?>/<?= $iq_result['max_score'] ?></p>
        <p><strong>Percentage:</strong> <?= round(($iq_result['score'] / $iq_result['max_score']) * 100) ?>%</p>
        <p><strong>Date:</strong> <?= $iq_result['created'] ?></p>
    <?php else: ?>
        <p>No IQ test taken yet.</p>
    <?php endif; ?>
</div>

<!-- EQ RESULT -->
<div class="card" style="background:white; padding:15px; border-radius:8px;">
    <h3>EQ Test</h3>

    <?php if ($eq_result): ?>
        <p><strong>Score:</strong> <?= $eq_result['score'] ?>/<?= $eq_result['max_score'] ?></p>
        <p><strong>Percentage:</strong> <?= round(($eq_result['score'] / $eq_result['max_score']) * 100) ?>%</p>
        <p><strong>Date:</strong> <?= $eq_result['created'] ?></p>
    <?php else: ?>
        <p>No EQ test taken yet.</p>
    <?php endif; ?>
</div>

<br>

<a class="btn" href="print_report.php?id=<?= $result['id'] ?>">Print Report</a>
<a class="btn" href="dashboard.php">Back to Dashboard</a>

<?php include "../inc/footer.php"; ?>
