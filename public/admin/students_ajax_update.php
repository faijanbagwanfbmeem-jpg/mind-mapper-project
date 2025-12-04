<?php
require_once "../../app/db.php";

// Accepts POST: student_id, left_brain_percent, right_brain_percent, iq_percent, eq_percent, counselor_sent, update_single
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

$sid = intval($_POST['student_id'] ?? 0);
$left = intval($_POST['left_brain_percent'] ?? 0);
$right = intval($_POST['right_brain_percent'] ?? 0);
$iq = intval($_POST['iq_percent'] ?? 0);
$eq = intval($_POST['eq_percent'] ?? 0);
$status = $_POST['counselor_sent'] ?? 'Pending';

$stmt = $mysqli->prepare("UPDATE student_info SET left_brain_percent=?, right_brain_percent=?, iq_percent=?, eq_percent=?, counselor_sent=? WHERE id=?");
$stmt->bind_param("iiiisi", $left, $right, $iq, $eq, $status, $sid);
if ($stmt->execute()) {
    echo "OK";
} else {
    http_response_code(500);
    echo "DB error";
}
