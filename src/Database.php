<?php
namespace Database

class Database {
    private $host = "178.128.173.36";
    private $port = 3306;
    private $username = "WEB_ACCESS";
    private $password = ""; // Reserver for Apache config global variable, might switch to SSH tunneling tho ¯\_(ツ)_/¯
    private $database = "rome_project_db";

    // Database init function, connects the user to the database
    public function __construct() {
        // TODO: SQL connect
    }
}
?>