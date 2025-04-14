<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../../public/login.php");
    exit;
}

require_once '../includes/database.php';

$db = new Database();
$pdo = $db->getConnection();

if (!isset($_SESSION['account']['role']) || $_SESSION['account']['role'] != 'Developer') {
    header("Location: ../../public/login.php");
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
        
        // Antarux NOTE: Dynamic base URL for XAMPP Server, VPS server wont need this
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $base_url = "$protocol://$host/Rome-Project-Web/public"; 
        $_SESSION['token_link'] = "$base_url/register.php?token=$token";
    }
    header("Location: ../../public/token.php");
    exit;
}