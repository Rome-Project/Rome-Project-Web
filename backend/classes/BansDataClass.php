<?php
require_once WEB_ROOT . 'backend/includes/Database.php';

class BansDataClass {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getDatabaseConnection();
    }

    // Setters

    // Returns if player is banned or not
    public function checkIfUserIsBanned($playerID) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM GameBans WHERE Player_ID = ?");
            $stmt->execute([$playerID]);
            $banData = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($banData) {
                return [true, "Player is banned"];
            }

            return [false, "Player is not banned"];
        } catch (PDOException $e) {
            return [false, "Failed to check ban status: " . $e->getMessage()];
        }
    }

    // Attempts to set a ban for passed player
    public function addBanForUser($playerID, $moderatorID, $reason, $duration) {
        try {
            $this->pdo->beginTransaction();

            $playerID = (int)$playerID;
            $moderatorID = (int)$moderatorID;
            $duration = (int)$duration;

            [$isBanned] = $this->checkIfUserIsBanned($playerID);
            if ($isBanned) {
                $this->pdo->rollBack();
                return [false, "Player is already banned"];
            }

            $stmt = $this->pdo->prepare("INSERT INTO GameBans (Player_ID, Moderator, Reason, Duration) VALUES (?, ?, ?, ?)");
            $stmt->execute([$playerID, $moderatorID, $reason, $duration]);

            $this->pdo->commit();
            return [true, "Player banned successfully"];
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return [false, "Failed to ban player: " . $e->getMessage()];
        }
    }

    // Attempts to unset ban for the passed player
    public function removeBanForUser($playerID) {
         try {
            $this->pdo->beginTransaction();

            $isBanned = $this->checkIfUserIsBanned($playerID);
            if (!$isBanned) {
                $this->pdo->rollBack();
                return [false, "Player is not banned"];
            }

            $stmt = $this->pdo->prepare("DELETE FROM GameBans WHERE Player_ID = ?");
            $stmt->execute([$playerID]);

            $this->pdo->commit();
            return [true, "Player unbanned successfully"];
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return [false, "Failed to unban player: " . $e->getMessage()];
        }
    }

    // Data Getters
    public function getBans() {  
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM GameBans");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getTotalBans() {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM GameBans");
            $stmt->execute();
            return (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (PDOException $e) {
            return 0;
        }
    }
}
?>