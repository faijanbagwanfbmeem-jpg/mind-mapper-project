<?php
require_once "../../app/db.php";
include "inc/header.php";

// Today's registrations
$reg = $mysqli->query("SELECT COUNT(*) AS c FROM student_info WHERE DATE(created)=CURDATE()")
              ->fetch_assoc()['c'];

// Today's exams
$exam = $mysqli->query("SELECT COUNT(*) AS c FROM exam_results WHERE DATE(created)=CURDATE()")
               ->fetch_assoc()['c'];

// Total students
$total_students = $mysqli->query("SELECT COUNT(*) AS c FROM student_info")
                         ->fetch_assoc()['c'];

// Total exams
$total_exams = $mysqli->query("SELECT COUNT(*) AS c FROM exam_results")
                      ->fetch_assoc()['c'];
?>

<h2>Dashboard</h2>

<div style="display:flex; gap:20px; flex-wrap:wrap;">

    <div style="padding:20px; background:white; border-radius:10px; width:220px;">
        <h3>Today's Registrations</h3>
        <p><?= $reg ?></p>
    </div>

    <div style="padding:20px; background:white; border-radius:10px; width:220px;">
        <h3>Today's Exams</h3>
        <p><?= $exam ?></p>
    </div>

    <div style="padding:20px; background:white; border-radius:10px; width:220px;">
        <h3>Total Students</h3>
        <p><?= $total_students ?></p>
    </div>

    <div style="padding:20px; background:white; border-radius:10px; width:220px;">
        <h3>Total Exams</h3>
        <p><?= $total_exams ?></p>
    </div>

    <!-- ⭐ NEW: COUNSELOR SCHOOL REPORT CARD -->
    <div style="padding:20px; background:white; border-radius:10px; width:220px;">
        <h3>School-wise Counselor Report</h3>
        <p>View counselor assigned for each school.</p>
        <a href="report_school_counselor.php"
           style="padding:8px 15px; background:black; color:white; border-radius:6px; display:inline-block; text-decoration:none;">
            Open Report
        </a>
    </div>

</div>

<?php include "inc/footer.php"; ?>
