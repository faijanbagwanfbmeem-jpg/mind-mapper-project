<?php
require_once "../../app/db.php";
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

$student_id = $_SESSION['student_id'];
$type = $_POST['exam_type'] ?? '';
$answers = $_POST['ans'] ?? [];

if (!$type || empty($answers)) {
    die("Invalid exam submission");
}

$score = 0;
$max_score = 10;

// IQ CHECKING
if ($type == "IQ") {
    foreach ($answers as $qid => $ans) {

        $stmt = $mysqli->prepare("SELECT correct_option FROM iq_questions WHERE id=? LIMIT 1");
        $stmt->bind_param("i", $qid);
        $stmt->execute();
        $correct = $stmt->get_result()->fetch_assoc()['correct_option'];

        if ($correct == $ans) {
            $score++;
        }
    }
}

// EQ CHECKING (Now same as IQ - A/B/C/D)
elseif ($type == "EQ") {
    foreach ($answers as $qid => $ans) {

        $stmt = $mysqli->prepare("SELECT correct_option FROM eq_questions WHERE id=? LIMIT 1");
        $stmt->bind_param("i", $qid);
        $stmt->execute();
        $correct = $stmt->get_result()->fetch_assoc()['correct_option'];

        if ($correct == $ans) {
            $score++;
        }
    }
}

// Save Result
$stmt = $mysqli->prepare("
    INSERT INTO exam_results (student_id, exam_type, score, max_score, created)
    VALUES (?, ?, ?, ?, NOW())
");
$stmt->bind_param("isii", $student_id, $type, $score, $max_score);
$stmt->execute();

$result_id = $mysqli->insert_id;

header("Location: result.php?id=" . $result_id);
exit;

?>
