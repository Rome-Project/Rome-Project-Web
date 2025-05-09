<?php
session_start();
require_once 'config.php';

if (isset($_GET['action'])) {
    if ($_GET['action'] === 'login') {
        require_once WEB_ROOT . 'backend/logic/login.php';
    } elseif ($_GET['action'] === 'logout') {
        require_once WEB_ROOT . 'backend/logic/logout.php';
    } elseif ($_GET['action'] === 'register') {
        require_once WEB_ROOT . 'backend/logic/register.php';
    } elseif ($_GET['action'] === 'token') {
        require_once WEB_ROOT . 'backend/logic/token.php';
    }
}
?>