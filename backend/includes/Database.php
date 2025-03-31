<?php
class Database {
    private $host = "178.128.173.36";
    private $port = 3306;
    private $username = "antarux";
    private $password = "";
    private $db_name = "rome_project_db";
    private $connection = null;

    public function __construct() {
        try {
            $this->connection = new \PDO(
                "mysql:host=$this->host;dbname=$this->db_name;port=$this->port;charset=utf8",
                $this->username,
                $this->password
            );
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch(\PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->connection;
    }
}
?>
