<?php
/*
session_start();

require_once '../backend/includes/Security.php';
require_once '../backend/includes/Database.php';


$token = generateToken();
$role = "Developer";
$moderator = "SystemAdmin";

$pdo = null;
$pdo = Database::getDatabaseConnection();
$pdo->beginTransaction();
    
try {
    $stmt = $pdo->prepare("INSERT INTO Registration_Token (Token, Role, Moderator) VALUES (?, ?, ?)");
    $stmt->execute([$token, $role, $moderator]);

    $pdo->commit();
    print("Token created successfully: ");

    $protocol = 'http';
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
        $protocol = 'https';
    }

    $host = $_SERVER['HTTP_HOST'];
    $base_url = "$protocol://$host";

    echo "$base_url/register.php?token=$token";

    return true;
} catch (PDOException $e) {
    $pdo->rollBack();
    print("Failed to create registration token: " . $e->getMessage());
    return false;
}

*/
?>