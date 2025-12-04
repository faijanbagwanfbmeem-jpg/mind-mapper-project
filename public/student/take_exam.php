<?php
require_once "../../app/db.php";
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

$type = $_POST['exam_type'] ?? '';

if ($type == "IQ") {
    $questions = $mysqli->query("SELECT * FROM iq_questions ORDER BY RAND() LIMIT 10");
} else {
    $questions = $mysqli->query("SELECT * FROM eq_questions ORDER BY RAND() LIMIT 10");
}

include "../inc/header.php";
?>

<h2><?= $type ?> Exam</h2>

<form method="post" action="submit_exam.php">
<?php
$i = 1;
while ($q = $questions->fetch_assoc()):
?>
<div class="qbox">
    <p><strong>Q<?= $i++ ?>.</strong> <?= htmlspecialchars($q['question']) ?></p>

    <?php if ($type == "IQ"): ?>

        <label><input type="radio" name="ans[<?= $q['id'] ?>]" value="A"> <?= $q['option_a'] ?></label><br>
        <label><input type="radio" name="ans[<?= $q['id'] ?>]" value="B"> <?= $q['option_b'] ?></label><br>
        <label><input type="radio" name="ans[<?= $q['id'] ?>]" value="C"> <?= $q['option_c'] ?></label><br>
        <label><input type="radio" name="ans[<?= $q['id'] ?>]" value="D"> <?= $q['option_d'] ?></label><br>

    <?php else: ?>

        <label><input type="radio" name="ans[<?= $q['id'] ?>]" value="A"> <?= $q['option_a'] ?></label><br>
        <label><input type="radio" name="ans[<?= $q['id'] ?>]" value="B"> <?= $q['option_b'] ?></label><br>
        <label><input type="radio" name="ans[<?= $q['id'] ?>]" value="C"> <?= $q['option_c'] ?></label><br>
        <label><input type="radio" name="ans[<?= $q['id'] ?>]" value="D"> <?= $q['option_d'] ?></label><br>

    <?php endif; ?>
</div>
<?php endwhile; ?>

<input type="hidden" name="exam_type" value="<?= $type ?>">
<button class="btn">Submit Exam</button>
</form>

<?php include "../inc/footer.php"; ?>
