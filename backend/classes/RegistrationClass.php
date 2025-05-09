<?php
require_once WEB_ROOT . 'backend/includes/Database.php';
include_once '../modules/Security.php';

class RegistrationClass {
    private $pdo

    // Registration class constructor
    public function __construct() {
        $this->pdo = Database::getDatabaseConnection();
    }

    // Creates a new action log for the action history
    public function createRegistrationToken(string $token, string $role, string $moderator): bool {
        try {
            $pdo->beginTransaction();
            
            $stmt = $pdo->prepare("INSERT INTO Registration_Token (Token, Role, Moderator) VALUES (?, ?, ?)");
            $stmt->execute([$token, $role, $moderator]);
    
            $pdo->commit();
            return true;
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log("Failed to create registration token: " . $e->getMessage());
            return false;
        }
    }

    // Returns token data for the passed token
    public function getTokenData(string $token): array {
        $stmt = $pdo->prepare("SELECT * FROM Registration_Token WHERE Token = ?"); 
        $stmt->execute([$token]);
        $token_data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $token_data;
    }

    // Sets the passed token as used
    public function setTokenAsUsed(string $token): bool {
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("UPDATE Registration_Token SET IsUsed = 1 WHERE Token = ?");
            $stmt->execute([$token]);

            $pdo->commit();
            return true;
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log("Failed to set token as used: " . $e->getMessage());
            return false;
        }
    }

    // Registers a new user with passed data
    function registerNewUser(string $username, string $password, string $role): bool {
        try {
            $pdo->beginTransaction();
            $hashedPassword = hashPassword($password);
           
            // Creating a new user
            $stmt = $pdo->prepare("INSERT INTO User (Username, Password, Role) VALUES (?, ?, ?)");
            $stmt->execute([$username, $hashedPassword, $role]);
        
            // Creating a new user settings
            $user_id = $pdo->lastInsertId();
            $stmt = $pdo->prepare("INSERT INTO User_Settings (user_id) VALUES (?)");
            $stmt->execute([$user_id]);

            $pdo->commit();
            return true;
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log("Registration error: " . $e->getMessage());
            return false;
        }
    }
}
?>