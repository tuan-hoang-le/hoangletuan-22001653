<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect authenticated users to product list, otherwise to login page.
if (isset($_SESSION['user_id'])) {
    header("Location: product_list.php");
} else {
    header("Location: login.php");
}

exit;
?>