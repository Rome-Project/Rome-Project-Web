<?php
require_once 'Database.php';
class BansData {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getDatabaseConnection();
    }

    // TODO: API Currently using these separately, move everything under this class
    public function CheckIfUserIsBanned() {}
    public function AddBanForUser() {}
    public function RemoveBanForUser() {}

    // Data Getters
    public function getBans() {  

    }

    public function getTotalBans() {

    }
}
?>