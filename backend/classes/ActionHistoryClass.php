<?php
require_once WEB_ROOT . 'backend/includes/Database.php';

class ActionHistoryClass {
    private $pdo;

    // ActionHistory class constructor
    public function __construct() {
        $this->pdo = Database::getDatabaseConnection();
    }

    // Creates a new action log for the action history
    public function createHistoryLog(int $moderator_id, string $action_info, string $severity): bool {            
        try {
            $this->pdo->beginTransaction();
            
            $stmt = $this->pdo->prepare("INSERT INTO Action_History (Moderator, Action_Info, Severity) VALUES (?, ?, ?)");
            $stmt->execute([$moderator_id, $action_info, $severity]);
    
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Failed to create action log: " . $e->getMessage());
            return false;
        }
    }
}
?>