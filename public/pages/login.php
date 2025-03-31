<?php
session_start();
require_once '../../backend/includes/Database.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new \Database();
    $pdo = $db->getConnection();
    
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $database_statement = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $database_statement->execute([$username]);
    $user = $database_statement->fetch(\PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password_hash'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
        
        $database_statement = $pdo->prepare("UPDATE users SET last_login = NOW(), last_ip = INET6_ATON(?) WHERE id = ?");
        $database_statement->execute([$ip, $user['id']]);
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['last_login'] = $user['last_login'];
        $_SESSION['last_ip'] = $user['last_ip'];

        header("Location: dashboard.php");
        exit;
    } else {
        $_SESSION['login_error'] = "Invalid username or password!";
        header("Location: ../index.php");
        exit;
    }
}
header("Location: ../index.php");
exit;
?>