<?php
require_once 'Database.php';
require_once 'Security.php';

// Creates a new action log for the action history
function createRegistrationToken($token, $role, $moderator) {
    $pdo = Database::getDatabaseConnection();
        
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
function getTokenData($token) {
    $pdo = Database::getDatabaseConnection();

    $stmt = $pdo->prepare("SELECT * FROM Registration_Token WHERE Token = ?"); 
    $stmt->execute([$token]);
    $token_data = $stmt->fetch(PDO::FETCH_ASSOC);

    return $token_data;
}

// Sets the passed token as used
function setTokenAsUsed($token) {
    $pdo = Database::getDatabaseConnection();

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
function registerNewUser($username, $password, $role) {
    $pdo = Database::getDatabaseConnection();
        
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
?>