<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: /login.php");  
    exit;
}

require_once WEB_ROOT . 'backend/includes/Database.php';
require_once WEB_ROOT . 'backend/modules/Security.php';
require_once WEB_ROOT . 'backend/classes/RegistrationClass.php';
require WEB_ROOT . 'backend/classes/UserClass.php';

$pdo = Database::getDatabaseConnection();
$UserClass = UserClass::getOrSaveUser($_SESSION['client']);
$RegistrationClass = new RegistrationClass();


if ($UserClass->getRole() !== 'Developer') {
    header("Location: /login.php");  
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'] ?? '';

    if (!in_array($role, ['Developer', 'Influencer', 'Player'])) {
        $_SESSION['token_error'] = "Invalid role";
    } else {
        $token = generateToken();
        $moderator = $UserClass->getUsername();
        
        $createTokenState = $RegistrationClass->createRegistrationToken($token, $role, $moderator);
        if ($createTokenState) {
            // Antarux NOTE: Dynamic base URL for XAMPP Web Server and VPS Server at the same time
            // https://www.php.net/manual/en/reserved.variables.server.php
            $protocol = 'http';
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
                $protocol = 'https';
            }

            $host = $_SERVER['HTTP_HOST'];
            $baseUrl = "$protocol://$host";

            $_SESSION['token_link'] = "$baseUrl/register.php?token=$token";
        } else {
            $_SESSION['token_error'] = "Failed to create token";
        }
    }
    
    header("Location: /token.php");
    exit;
}
?>