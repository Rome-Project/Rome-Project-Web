<?php
class Database {
    private $host = "178.128.173.36";
    private $port = 3306;
    private $username = "antarux";
    private $password = "";
    private $db_name = "rome_project_db";
    private $connection = null;

    /*
        - Database class Constructor
        - Establishes a connection to the database using PDO
    */
    public function __construct() {
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

    // Returns the PDO database connection Instance
    public function getConnection() {
        return $this->connection;
    }

    /*
        - Database class Destructor
        - Closes the database connection when the object is destroyed
    */
    public function __destruct() {
        $this->connection = null;
    }

    // Returns the user data for the given user ID
    public function getUserById($userId) {
        // Antarux NOTE: Do not use * in SELECT statements, always specify the columns you need for user rows as selecting all can expose password
        $stmt = $this->connection->prepare("SELECT id, username, email, created_at, last_login, last_ip, is_enabled, role FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*
        - Updates the user data with the gived user ID and data array
        - The data array should contain the fields to be updated and their new values
        - Returns the updated user data
    */
    public function updateUserData($userId, $data) {
        $this->connection->beginTransaction();

        // Antarux NOTE: We need to remove sensitive fields from the $data array to prevent accidental updates of these fields
        if (isset($data['id'])) {
            unset($data['id']);
        }
        if (isset($data['password_hash'])) {
            unset($data['password_hash']);
        }

        $fields = array_keys($data);
        $placeholders = implode(', ', array_map(function($field) {
            return "$field = ?";
        }, $fields));
        $values = array_values($data);
        $values[] = $userId;

        $stmt = $this->connection->prepare("UPDATE users SET $placeholders WHERE id = ?");
        $stmt->execute($values);

        $updatedUser = $this->getUserById($userId);
        $this->connection->commit();

        return $updatedUser;
    }
}
?>
