<?php
if (!isset($_SESSION)) session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Panel</title>
<style>
body { font-family: Arial; margin:0; padding:0; background:#eef2f5; }
.topbar { background:black; color:white; padding:15px; font-size:20px; }
.menu { background:#222; width:200px; height:100vh; float:left; padding-top:20px; }
.menu a { display:block; padding:12px; color:white; text-decoration:none; }
.menu a:hover { background:#444; }
.content { margin-left:200px; padding:20px; }
</style>
</head>
<body>

<div class="topbar">
Admin Panel - MindMapper
<div style="float:right;">
<a href="logout.php" style="color:white;">Logout</a>
</div>
</div>

<div class="menu">
<a href="dashboard.php">Dashboard</a>
<a href="students.php">Students</a>
<a href="iq_questions.php">IQ Questions</a>
<a href="eq_questions.php">EQ Questions</a>
<a href="results.php">Exam Results</a>
<a href="report.php">Reports</a>
</div>

<div class="content">
