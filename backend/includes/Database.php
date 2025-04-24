<?php
class Database {
    private $host = "178.128.173.36";
    private $port = 3306;
    private $username = "antarux";
    private $password = "";
    private $db_name = "rome_project_db";

    private $connection = null;
    private static $instance = null;

    /*
        - Database class Constructor
        - Establishes a connection to the database using PDO
        - Not callable directly from outside of the class to prevent multiple connections
    */
    private function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host=$this->host;dbname=$this->db_name;port=$this->port;charset=utf8",
                $this->username,
                $this->password
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            exit;
        }
    }

    /* 
        - Returns the PDO Instance of the Database class 
        - If the Instance is null, we call the constructor and create a new instance & connection
    */
    public static function getPDOInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    // Returns the PDO database connection
    public function getConnection() {
        return $this->connection;
    }

    // Returns the singleton Database class
    public static function getDatabaseConnection() {
        return self::getPDOInstance()->getConnection();
    }

    /*
        - Database class Destructor
        - Closes the database connection when the object is destroyed
    */
    public function __destruct() {
        $this->connection = null;
    }
}
?>
