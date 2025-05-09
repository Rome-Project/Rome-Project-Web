<?php 
// Antarux NOTE: We can consider this as super global module lol
session_start();

require_once dirname(__DIR__) . '/config.php'; // Antarux NOTE: Since we are accessing this file from multiple directories, we need to require it from the root directory

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

require_once WEB_ROOT . 'backend/classes/UserClass.php';
require_once WEB_ROOT . 'backend/includes/Database.php';

$pdo= Database::getDatabaseConnection();
$UserClass = UserClass::getOrSaveUser($_SESSION['client']);
?>