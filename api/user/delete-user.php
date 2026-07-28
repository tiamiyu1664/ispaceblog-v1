<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type");

include 'usersHandler.php';

$userID = $_GET['id'] ?? $_POST['UserID'] ?? '';

if (!empty($userID)) {
    $handler = new UsersHandler();
    $result = $handler->deleteUser($userID);
    echo json_encode($result);
} else {
    echo json_encode([
        "success" => "No",
        "message" => "UserID is required"
    ]);
}
?>
