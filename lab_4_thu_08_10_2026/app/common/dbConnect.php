<?php
require_once __DIR__ . '/config.php';

// Enable strict error reporting to throw exceptions on MySQL errors.
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Establish a new MySQL connection.
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

    // Set charset to utf8mb4 for complete Unicode support.
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    // Terminate execution safely and show a user-friendly error message.
    die("Database connection failed: " . htmlspecialchars($e->getMessage()));
}
?>