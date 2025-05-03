<?php
header("Content-Type: application/json");
require_once '../../backend/includes/Database.php';

$server_token = getenv("CHECKBAN_API_TOKEN");
$headers = apache_request_headers(); // https://www.php.net/manual/en/function.apache-request-headers.php
$auth_header = $headers["Authorization"] ?? "";

if ($server_token === null) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Server token config error"]);
    exit;
}

if ($auth_header !== "Bearer $server_token") {
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

$player_id = $data["player"];

if (!is_numeric($player_id)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Invalid data type"]);
    exit;
}

$pdo = Database::getDatabaseConnection();

try {
    $stmt = $pdo->prepare("SELECT * FROM GameBans WHERE Player_ID = ?");
    $stmt->execute([$player_id]);
    $fetchedData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($fetchedData) {
        http_response_code(200);
        echo json_encode(["success" => true, "response" => true, "message" => "Player is banned", "banDetails" => $fetchedData]);
    } else {
        http_response_code(200);
        echo json_encode(["success" => true, "response" => false, "message" => "Player is not banned"]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to check ban status for user: ' . $e->getMessage()]);
}
?>
