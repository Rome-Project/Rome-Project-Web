<?php
session_start();
require_once '../includes/database.php';

$db = new Database();
$pdo = $db->getConnection();

$token = $_GET['token'] ?? '';
if (!$token) {
    header("Location: ../../public/login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM registration_tokens WHERE token = ? AND used = 0");
$stmt->execute([$token]);
$token_data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$token_data) {
    $_SESSION['register_error'] = "Invalid or used token.";
    header("Location: ../../public/register.php?token=$token");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (strlen($password) < 15 || !preg_match('/[0-9]/', $password) || !preg_match('/[\W]/', $password)) {
        $_SESSION['register_error'] = "Password must be 15+ characters with numbers and special characters.";
    } elseif ($password !== $confirm_password) {
        $_SESSION['register_error'] = "Passwords do not match.";
    } else {
        $pdo->beginTransaction();
        try {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $ip = $_SERVER['REMOTE_ADDR'];
            $role = $token_data['role'];
            
            $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, role, last_ip) VALUES (?, ?, ?, INET6_ATON(?))");
            $stmt->execute([$username, $password_hash, $role, $ip]);
            
            $user_id = $pdo->lastInsertId();
            $stmt = $pdo->prepare("INSERT INTO user_settings (user_id) VALUES (?)");
            $stmt->execute([$user_id]);
            
            $stmt = $pdo->prepare("UPDATE registration_tokens SET used = 1 WHERE token = ?");
            $stmt->execute([$token]);
            
            $stmt = $pdo->prepare("INSERT INTO action_history (user_id, action, severity) VALUES (?, 'User registered', 'low')");
            $stmt->execute([$user_id]);
            
            $pdo->commit();
            header("Location: ../../public/login.php");
            exit;
        } catch (PDOException $e) {
            $pdo->rollBack();

            $_SESSION['register_error'] = "Registration failed. Please try again.";
            error_log("Registration error: " . $e->getMessage());
            header("Location: ../../public/register.php?token=$token");
            
            exit;
        }
    }
}

header("Location: ../../public/register.php?token=$token");
exit;