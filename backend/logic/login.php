<?php
session_start();
require_once WEB_ROOT . 'backend/includes/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();
    $pdo = $db->getConnection();
    
    $username = trim($_POST['username'] ?? '');
    if (empty($username)) {
        $_SESSION['login_error'] = "Invalid username format!";
        header("Location: /login.php"); 
        exit;
    }
    
    $password = $_POST['password'] ?? '';
    if (empty($password)) {
        $_SESSION['login_error'] = "Password is required!";
        header("Location: /login.php"); 
        exit;
    }
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password_hash'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $currentTime = date('Y-m-d H:i:s');

        $user = $db->updateUserData($user['id'], [
            'last_login' => $currentTime,
            'last_ip' => $ip,
        ]);

        $_SESSION['logged_in'] = true;
        $_SESSION['account'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'] ?? null,
            'role' => $user['role'], 
            'last_login' => $user['last_login'],
            'last_ip' => $user['last_ip'],
            'created_at' => $user['created_at'],
            'is_enabled' => $user['is_enabled'],
        ];

        header("Location: /dashboard.php");
        exit;
    } else {
        $_SESSION['login_error'] = "Invalid username or password!";
        header("Location: /login.php"); 
        exit;
    }
}

// Antarux NOTE: This redirect is for when the script is accessed directly without POST login data
header("Location: /login.php");
exit;
?>
