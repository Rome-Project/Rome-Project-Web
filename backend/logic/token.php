<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: /login.php");  
    exit;
}

require_once WEB_ROOT . 'backend/includes/Database.php';

$db = new Database();
$pdo = $db->getConnection();

if (!isset($_SESSION['account']['role']) || $_SESSION['account']['role'] != 'Developer') {
    header("Location: /login.php");  
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'] ?? '';

    if (!in_array($role, ['Developer', 'Influencer', 'Player'])) {
        $_SESSION['token_error'] = "Invalid role";
    } else {
        $token = bin2hex(random_bytes(32));
        
        $stmt = $pdo->prepare("INSERT INTO registration_tokens (token, role) VALUES (?, ?)");
        $stmt->execute([$token, $role]);

        // Antarux NOTE: Dynamic base URL for XAMPP Server and VPS Server at the same time
        $protocol = 'http';
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $protocol = 'https';
        }
        $host = $_SERVER['HTTP_HOST'];
        $base_url = "$protocol://$host";

        $_SESSION['token_link'] = "$base_url/register.php?token=$token";
    }
    header("Location: /token.php");
    exit;
}