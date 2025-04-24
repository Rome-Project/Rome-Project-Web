<?php 
// Antarux NOTE: We can consider this as super global module lol
session_start();

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

require '../backend/includes/User.php';
require_once '../backend/includes/Database.php';

$user = User::getOrSaveUser($_SESSION['client']);
$pdo= Database::getDatabaseConnection();
?>