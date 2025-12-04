<?php
require_once "../../app/db.php";
session_start();

// If not logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

$student_id = $_SESSION['student_id'];
$username = $_SESSION['username'];

// -------------------------------------------
// 1. FETCH OR AUTO-CREATE STUDENT INFO
// -------------------------------------------

$stmt = $mysqli->prepare("SELECT * FROM student_info WHERE user_id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$info = $stmt->get_result()->fetch_assoc();

// If student_info does NOT exist → CREATE IT
if (!$info) {

    $insert = $mysqli->prepare("
        INSERT INTO student_info (user_id, full_name, counselor_sent, created)
        VALUES (?, ?, 'No', NOW())
    ");
    $insert->bind_param("is", $student_id, $username);
    $insert->execute();

    // Fetch again
    $stmt = $mysqli->prepare("SELECT * FROM student_info WHERE user_id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $info = $stmt->get_result()->fetch_assoc();
}

// -------------------------------------------
// 2. FETCH STUDENT RESULTS
// -------------------------------------------

$res = $mysqli->prepare("
    SELECT * FROM exam_results 
    WHERE student_id = ? 
    ORDER BY created DESC
");
$res->bind_param("i", $student_id);
$res->execute();
$results = $res->get_result();

include "../inc/header.php";
?>

<h2>Welcome, <?= htmlspecialchars($username) ?></h2>

<!-- EXAM BUTTONS -->
<div style="margin-bottom:20px;">
    <a class="btn" href="exam_start.php?type=IQ">Take IQ Test</a>
    <a class="btn" href="exam_start.php?type=EQ">Take EQ Test</a>
    <a class="btn" href="combined_report.php">View Combined Result</a>
    <a class="btn" href="logout.php" style="background:red;">Logout</a>
</div>

<!-- COUNSELOR BUTTON -->
<div style="margin:20px 0;">
    <form action="notify_counselor.php" method="POST">
        <button class="btn" style="background:#0077cc;">
            Request Counselor
        </button>
    </form>
</div>

<!-- STUDENT DETAILS -->
<div class="card" style="background:white; padding:15px; border-radius:8px; margin-bottom:20px;">
    <h3>Your Information</h3>
    <p><strong>Name:</strong> <?= $info['full_name'] ?></p>
    <p><strong>Counselor Request Sent:</strong> <?= $info['counselor_sent'] ?></p>
    <p><strong>Registered On:</strong> <?= $info['created'] ?></p>
</div>

<!-- RESULTS TABLE -->
<h3>Your Test Results</h3>

<table border="1" cellpadding="10" style="width:100%; background:white; border-collapse: collapse;">
<tr>
    <th>Exam</th>
    <th>Score</th>
    <th>Out of</th>
    <th>Date</th>
    <th>Action</th>
</tr>

<?php while ($row = $results->fetch_assoc()): ?>
<tr>
    <td><?= $row['exam_type'] ?></td>
    <td><?= $row['score'] ?></td>
    <td><?= $row['max_score'] ?></td>
    <td><?= $row['created'] ?></td>
    <td><a class="btn" href="result.php?id=<?= $row['id'] ?>">View</a></td>
</tr>
<?php endwhile; ?>
</table>

<?php include "../inc/footer.php"; ?>
