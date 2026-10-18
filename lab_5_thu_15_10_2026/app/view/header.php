<?php
// Ensure session is active to inspect user authentication state.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping cart management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .error {
            color: #ff0000;
            margin-bottom: 15px;
        }
        .success {
            color: #00ff00;
            margin-bottom: 15px;
        }
        /* Unified button styling for <button> and <a class="btn"> */
        button, .btn {
            display: inline-block;
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            padding: 6px 12px;
            text-decoration: none;
            border: 1px solid #ccc;
            background-color: #eee;
            color: #333;
            margin-right: 5px;
            cursor: pointer;
            vertical-align: middle;
            box-sizing: border-box;
            border-radius: 3px;
        }
        button:hover, .btn:hover {
            background-color: #ddd;
        }
        /* Input text and password styling */
        input[type="text"], input[type="password"] {
            font-family: Arial, sans-serif;
            font-size: 14px;
            padding: 6px 8px;
            border: 1px solid #ccc;
            border-radius: 3px;
            width: 320px;
            box-sizing: border-box;
        }
        nav {
            margin-bottom: 20px;
        }
        .user-panel {
            float: right;
            line-height: 32px;
        }
        .clearfix {
            clear: both;
        }
    </style>
</head>
<body>
    <h1>Shopping cart management</h1>
    <nav>
        <?php if (isset($_SESSION['user_id'])): ?>
            <!-- Navigation links for authenticated users -->
            <a href="product_list.php" class="btn">Product list</a>
            <a href="product_add.php" class="btn">Add new product</a>

            <!-- Display current logged-in username and logout link -->
            <span class="user-panel">
                Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></strong>
                <a href="logout.php" class="btn" style="margin-left: 8px;">Logout</a>
            </span>
        <?php else: ?>
            <!-- Navigation links for guests -->
            <a href="login.php" class="btn">Login</a>
            <a href="register.php" class="btn">Register</a>
        <?php endif; ?>
    </nav>
    <div class="clearfix"></div>
    <hr>