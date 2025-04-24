<?php
require_once WEB_ROOT . 'backend/includes/Database.php';
require_once WEB_ROOT . 'backend/includes/Security.php';
require_once WEB_ROOT . 'backend/includes/ActionHistory.php';
require_once WEB_ROOT . 'backend/includes/Registration.php';
require WEB_ROOT . 'backend/includes/User.php';

$pdo = Database::getDatabaseConnection();
$token = $_GET['token'] ?? '';

if (!$token) {
    header("Location: /login.php");  
    exit;
}

$token_data = getTokenData($token);
$moderator = $token_data['Moderator'] ?? "System";

if (!$token_data) {
    header("Location: /login.php");  
    exit;
}

if ($token_data['IsUsed']) {
    $_SESSION['register_error'] = "Token already used.";
    header("Location: /register.php?token=$token");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validation
    if (empty($username) || empty($password)) {
        $_SESSION['register_error'] = "Username and password are required.";
        header("Location: /register.php?token=$token");
        exit;
    }

    if ($password !== $confirm_password) {
        $_SESSION['register_error'] = "Passwords do not match.";
        header("Location: /register.php?token=$token");
        exit;
    }

    if (!validatePasswordStrength($password)) {
        header("Location: /register.php?token=$token");
        exit;
    }
    
    $registeredNewUserState = registerNewUser($username, $password, $token_data['Role']);
    if ($registeredNewUserState) {
        setTokenAsUsed($token);
        createHistoryLog($moderator, "User registered with username: " . $username, 'low');
        header("Location: /login.php");  
        exit;
    } else {
        $_SESSION['register_error'] = "Registration failed. Please try again";
        header("Location: /register.php?token=$token");
        exit;
    }
}

header("Location: /register.php?token=$token");
exit;
?>