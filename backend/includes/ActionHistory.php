<?php
require_once 'Database.php';

// Creates a new action log for the action history
function createHistoryLog($moderator_id, $action_info, $severity) {
    $pdo = Database::getDatabaseConnection();
        
    try {
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("INSERT INTO Action_History (Moderator, Action_Info, Severity) VALUES (?, ?, ?)");
        $stmt->execute([$moderator_id, $action_info, $severity]);

        $pdo->commit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Failed to create action log: " . $e->getMessage());
        exit;
    }
}
?>