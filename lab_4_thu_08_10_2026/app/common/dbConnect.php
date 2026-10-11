<?php
require_once __DIR__ . '/config.php';

// Establish a new MySQLi connection using defined constants.
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// Handle connection failure gracefully.
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Set character set to UTF-8 to correctly handle Unicode characters.
$conn->set_charset("utf8mb4");
?>