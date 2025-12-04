<?php
require_once __DIR__ . '/config.php';

// Create Database Connection
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($mysqli->connect_error) {
    die("Database Connection Failed: " . $mysqli->connect_error);
}

// Ensure UTF-8 database communication
$mysqli->set_charset('utf8mb4');
?>
