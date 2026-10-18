<?php
require_once __DIR__ . '/../common/dbConnect.php';

/* 
 * Retrieve a user record from the database by username.
 */
function getUserByUsername($username) {
    global $conn;
    $query = "SELECT id, username, password FROM " . TABLE_USERS . " WHERE username = ?";

    try {
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $stmt->close();

            return $user;
        }

        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        error_log("Error retrieving user: " . $e->getMessage());
    }

    return null;
}

/* 
 * Check whether a username already exists in the database.
 */
function checkUserExists($username) {
    return getUserByUsername($username) !== null;
}

/* 
 * Register a new user by hashing the password and inserting into database.
 */
function registerUser($username, $password) {
    global $conn;
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO " . TABLE_USERS . " (username, password) VALUES (?, ?)";

    try {
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $username, $hashedPassword);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    } catch (mysqli_sql_exception $e) {
        error_log("Error registering user: " . $e->getMessage());

        return false;
    }
}

/* 
 * Validate registration input fields.
 */
function validateRegistrationInput($username, $password) {
    $errors = [];

    if ($username === '') {
        $errors[] = "Username cannot be empty.";
    } elseif (mb_strlen($username) > 50) {
        $errors[] = "Username cannot exceed 50 characters.";
    } elseif (checkUserExists($username)) {
        $errors[] = "Username is already registered. Please choose another.";
    }

    if ($password === '') {
        $errors[] = "Password cannot be empty.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }

    return $errors;
}

/* 
 * Authenticate a user by username and password.
 */
function authenticateUser($username, $password) {
    $user = getUserByUsername($username);

    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }

    return false;
}

/* 
 * Access control guard: verify that the user is currently logged in.
 * If not authenticated, redirect user immediately to login.php
 */
function requireLogin() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
}

/* 
 * Check if a session has an authenticated user.
 */
function isLoggedIn() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    return isset($_SESSION['user_id']);
}
?>