<?php
/*
https://stackoverflow.com/questions/47630160/how-to-use-definitions-define-variable-in-include
https://stackoverflow.com/questions/40061474/how-to-deal-with-include-files-and-relative-paths-in-my-project
https://stackoverflow.com/questions/8668776/get-root-directory-path-of-a-php-project
*/
define('WEB_ROOT', dirname(dirname(__FILE__)) . '/');

session_start();

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