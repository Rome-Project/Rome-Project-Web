<?php
require_once WEB_ROOT . 'backend/includes/Database.php';
include_once '../modules/Security.php';

class RegistrationClass {
    private $pdo;

    // Registration class constructor
    public function __construct() {
        $this->pdo = Database::getDatabaseConnection();
    }

    // Creates a new action log for the action history
    public function createRegistrationToken(string $token, string $role, string $moderator): bool {
        try {
            $this->pdo->beginTransaction();
            
            $stmt = $this->pdo->prepare("INSERT INTO Registration_Token (Token, Role, Moderator) VALUES (?, ?, ?)");
            $stmt->execute([$token, $role, $moderator]);
    
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Failed to create registration token: " . $e->getMessage());
            return false;
        }
    }

    // Returns token data for the passed token
    public function getTokenData(string $token): array {
        $stmt = $this->pdo->prepare("SELECT * FROM Registration_Token WHERE Token = ?"); 
        $stmt->execute([$token]);
        $token_data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $token_data;
    }

    // Sets the passed token as used
    public function setTokenAsUsed(string $token): bool {
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare("UPDATE Registration_Token SET IsUsed = 1 WHERE Token = ?");
            $stmt->execute([$token]);

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Failed to set token as used: " . $e->getMessage());
            return false;
        }
    }

    // Registers a new user with passed data
    function registerNewUser(string $username, string $password, string $role): bool {
        try {
            $this->pdo->beginTransaction();
            $hashedPassword = hashPassword($password);
           
            // Creating a new user
            $stmt = $this->pdo->prepare("INSERT INTO User (Username, Password, Role) VALUES (?, ?, ?)");
            $stmt->execute([$username, $hashedPassword, $role]);
        
            // Creating a new user settings
            $user_id = $this->pdo->lastInsertId();
            $stmt = $this->pdo->prepare("INSERT INTO User_Settings (user_id) VALUES (?)");
            $stmt->execute([$user_id]);

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Registration error: " . $e->getMessage());
            return false;
        }
    }
}
?>