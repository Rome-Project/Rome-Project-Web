<?php
require_once 'Database.php';

class User {
    private $userid;
    private $username;
    private $email;
    private $role;
    private $created_at;
    private $last_login;
    private $is_enabled;

    private $loaded = false;

    /*
        - User class Constructor
        - If the user (class) already exists in the cache, we will return it
    */
    public function __construct($username) {
        $this->username = $username;
        $this->loadUserData();
    }

    // Loads saved user data
    private function loadUserData() {
        $pdo = Database::getDatabaseConnection();
        // Antarux NOTE: Do not use * in SELECT statements, we need to always specify the columns we need for user rows as selecting all can expose for example password
        $stmt = $pdo->prepare("SELECT User_ID, Username, Email, Role, Created_At, Last_Login, IsEnabled FROM User WHERE Username = ?");
        $stmt->execute([$this->username]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $this->userid = $data['User_ID'];
            $this->username = $data['Username'];
            $this->email = $data['Email'];
            $this->role = $data['Role'];
            $this->created_at = $data['Created_At'];
            $this->last_login = $data['Last_Login'];
            $this->is_enabled = $data['IsEnabled'];
            $this->loaded = true;

            // TODO: Load User Settings as well :D
        }
    }
    
    // Class super (static) method to return user object
    public static function getOrSaveUser($username) {
        if (isset($_SESSION['user_cache'][$username])) {
            $user = unserialize($_SESSION['user_cache'][$username]);
            return $user;
        }

        $user = new self($username);
        if ($user->getIsLoaded()) {
            $_SESSION['user_cache'][$username] = serialize($user);
        }

        return $user;
    }

    /* 
        - Safely updates user data with the provided array
        - Sensitive data is removed from the array to prevent accidental updates
    */
    public function updateUserData($data) {
        if (!$this->loaded) {
            throw new Exception("Failed to update user data, user is not loaded");
        }

        $pdo = Database::getDatabaseConnection();
        $pdo->beginTransaction();
        
        try {
            // Antarux NOTE: We need to remove sensitive fields from the $data array to prevent accidental updates of these fields
            if (isset($data['User_ID'])) { unset($data['User_ID']); }
            if (isset($data['Password'])) { unset($data['Password']); }
            if (isset($data['Created_At'])) { unset($data['Created_At']); }
            if (isset($data['Username'])) { unset($data['Username']); }
            if (isset($data['Email'])) { unset($data['Email']); }

            $fields = array_keys($data);
            $placeholders = implode(', ', array_map(function($field) {
                return "$field = ?";
            }, $fields));
            $values = array_values($data);
            $values[] = $this->userid;

            $stmt = $pdo->prepare("UPDATE User SET $placeholders WHERE User_ID = ?");
            $stmt->execute($values);

            $pdo->commit();

            // Antarux NOTE: Updating session cache for the user to reflect the updates data
            $_SESSION['user_cache'][$this->username] = serialize($this);
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log("Failed to update data for " . $this->username . ", " . $e->getMessage());
            exit;
        }
    }

    // Getters
    public function getUserID() { return $this->userid; }
    public function getUsername() { return $this->username; }
    public function getEmail() { return $this->email; } // Prob not wise to expose this
    public function getRole() { return $this->role; }
    public function getCreatedAt() { return $this->created_at; }
    public function getLastLogin() { return $this->last_login; }
    public function getIsEnabled() { return $this->is_enabled; }
    public function getIsLoaded() { return $this->loaded; }

    // Setters
    // TODO, also what do we even want to set lmao
}
?>