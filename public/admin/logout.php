<?php
session_start();

// Remove all admin session variables
unset($_SESSION['admin_id']);
unset($_SESSION['admin_username']);

// Destroy session
session_destroy();

// Redirect to home page
header("Location: ../index.php");
exit;
?>
