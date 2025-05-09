<?php
require_once WEB_ROOT . 'backend/includes/Database.php';
require_once WEB_ROOT . 'backend/modules/Security.php';
require_once WEB_ROOT . 'backend/classes/ActionHistoryClass.php';
require_once WEB_ROOT . 'backend/classes/RegistrationClass.php';
require WEB_ROOT . 'backend/classes/UserClass.php';

$pdo = Database::getDatabaseConnection();
$RegistrationClass = new Registration();
$ActionHistoryClass = new ActionHistory();

$token = $_GET['token'] ?? '';

if (!$token) {
    header("Location: /login.php");  
    exit;
}

$tokenData = getTokenData($token);
$moderator = $tokenData['Moderator'] ?? "System";

if (!$tokenData) {
    header("Location: /login.php");  
    exit;
}

if ($tokenData['IsUsed']) {
    $_SESSION['register_error'] = "Token already used.";
    header("Location: /register.php?token=$token");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']) ?? '';
    $password = htmlspecialchars($_POST['password']) ?? '';
    $confirmPassword = htmlspecialchars($_POST['confirmPassword']) ?? '';
    
    // Validation
    if (empty($username) || empty($password)) {
        $_SESSION['register_error'] = "Username and password are required.";
        header("Location: /register.php?token=$token");
        exit;
    }

    if ($password !== $confirmPassword) {
        $_SESSION['register_error'] = "Passwords do not match.";
        header("Location: /register.php?token=$token");
        exit;
    }

    if (!validatePasswordStrength($password)) {
        header("Location: /register.php?token=$token");
        exit;
    }
    
    $registeredNewUserState = $RegistrationClass->registerNewUser($username, $password, $tokenData['Role']);
    if ($registeredNewUserState) {
        $RegistrationClass->setTokenAsUsed($token);
        $ActionHistoryClass->createHistoryLog($moderator, "User registered with username: " . $username, 'low');
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