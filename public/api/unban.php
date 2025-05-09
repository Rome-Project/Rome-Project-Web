<?php
header("Content-Type: application/json");
require_once '../../backend/includes/Database.php';

$serverToken = getenv("UNBAN_API_TOKEN");
$headers = apache_request_headers(); // https://www.php.net/manual/en/function.apache-request-headers.php
$authHeader = $headers["Authorization"] ?? "";

if ($serverToken === null) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Server token config error"]);
    exit;
}

if ($authHeader !== "Bearer $serverToken") {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

/*
https://www.php.net/manual/en/function.file-get-contents.php
https://stackoverflow.com/questions/16884155/what-is-the-difference-between-request-and-file-get-contentsphp-data
https://stackoverflow.com/questions/8270830/use-file-get-contents
*/
$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data["player"])) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Missing required parameter"]);
    exit;
}

$playerId = $data["player"];

if (!is_numeric($playerId)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Invalid data type"]);
    exit;
}

$pdo = Database::getDatabaseConnection();

try {
    $pdo->beginTransaction();
    
    $stmt = $pdo->prepare("SELECT * FROM GameBans WHERE Player_ID = ?");
    $stmt->execute([$playerId]);
    $fetchedData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$fetchedData) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Player is not banned']);
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM GameBans WHERE Player_ID = ?");
    $stmt->execute([$playerId]);

    $pdo->commit();

    http_response_code(200);
    echo json_encode(["success" => true, "message" => "Successfully unbanned user"]);
    exit;
} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to unban user: ' . $e->getMessage()]);
    exit;
}
?>
