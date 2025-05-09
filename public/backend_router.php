<?php
session_start();
require_once 'config.php';

if (isset($_GET['action'])) {
    if ($_GET['action'] === 'login') {
        require_once WEB_ROOT . 'backend/logic/LoginLogic.php';
    } elseif ($_GET['action'] === 'logout') {
        require_once WEB_ROOT . 'backend/logic/LogoutLogic.php';
    } elseif ($_GET['action'] === 'register') {
        require_once WEB_ROOT . 'backend/logic/RegisterLogic.php';
    } elseif ($_GET['action'] === 'token') {
        require_once WEB_ROOT . 'backend/logic/TokenLogic.php';
    }
}
?>