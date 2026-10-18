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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $errors = validateRegistrationInput($username, $password);

    if (empty($errors)) {
        if (registerUser($username, $password)) {
            $_SESSION['flash_success'] = "Registration successful! Please log in with your credentials.";
            header("Location: login.php");
            exit;
        } else {
            $errors[] = "Registration failed due to a system error. Please try again.";
        }
    }
}

include 'view/header.php';
?>

<h2>Register an account</h2>

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
        <label for="password">Password (at least 6 characters):</label><br>
        <input type="password" id="password" name="password" required>
    </p>
    <p>
        <button type="submit" class="btn">Register</button>
        <a href="login.php" class="btn">Already have an account? Login</a>
    </p>
</form>

<?php include 'view/footer.php'; ?>