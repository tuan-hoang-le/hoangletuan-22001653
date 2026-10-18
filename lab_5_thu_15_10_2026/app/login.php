<?php
require_once 'model/user.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isLoggedIn()) {
    header("Location: product_list.php");
    exit;
}

$errors = [];
$username = '';
$flashSuccess = $_SESSION['flash_success'] ?? '';
unset($_SESSION['flash_success']);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $errors[] = "Please enter both username and password.";
    } else {
        $user = authenticateUser($username, $password);

        if ($user) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: product_list.php");
            exit;
        } else {
            $errors[] = "Invalid username or password.";
        }
    }
}

include 'view/header.php';
?>

<h2>Login</h2>

<?php if ($flashSuccess): ?>
    <div class="success"><?php echo htmlspecialchars($flashSuccess); ?></div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <div class="error">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" action="">
    <p>
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
    </p>
    <p>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required>
    </p>
    <p>
        <button type="submit" class="btn">Login</button>
        <a href="register.php" class="btn">Do not have an account? Register</a>
    </p>
</form>

<?php include 'view/footer.php'; ?>