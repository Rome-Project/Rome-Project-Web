<?php
session_start();

require_once WEB_ROOT . 'backend/includes/Database.php';
require_once WEB_ROOT . 'backend/includes/Security.php';
require WEB_ROOT . 'backend/includes/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = Database::getDatabaseConnection();
    
    $username = trim($_POST['username'] ?? '');
    if (empty($username)) {
        $_SESSION['login_error'] = "Username is required";
        header("Location: /login.php"); 
        exit;
    }
    
    $password = $_POST['password'] ?? '';
    if (empty($password)) {
        $_SESSION['login_error'] = "Password is required";
        header("Location: /login.php"); 
        exit;
    }

    $stmt = $pdo->prepare("SELECT Password FROM User WHERE Username = ?");
    $stmt->execute([$username]);
    $fetchedData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (verifyPassword($password, $fetchedData['Password'])) {
        $user = User::getOrSaveUser($username);
        $user->updateUserData([
            'Last_Login' => date('Y-m-d H:i:s'),
        ]);

        $_SESSION['logged_in'] = true;
        $_SESSION['client'] = $user->getUsername();

        header("Location: /dashboard.php");
        exit;
    } else {
        $_SESSION['login_error'] = "Invalid username or password";
        header("Location: /login.php"); 
        exit;
    }
}

header("Location: /login.php");
exit;
?>
