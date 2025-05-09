<?php
require_once WEB_ROOT . 'backend/includes/Database.php';

class BansDataClass {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getDatabaseConnection();
    }

    // TODO: API Currently using these separately, move everything under this class
    public function checkIfUserIsBanned() {}
    public function addBanForUser() {}
    public function removeBanForUser() {}

    // Data Getters
    public function getBans() {  

    }

    public function getTotalBans() {

    }
}
?>