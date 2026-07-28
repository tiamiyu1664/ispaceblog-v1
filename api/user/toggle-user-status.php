<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type");

include 'usersHandler.php';

$userID = $_GET['id'] ?? $_POST['UserID'] ?? '';
$status = $_GET['status'] ?? $_POST['StatusID'] ?? '';

if (!empty($userID) && !empty($status)) {
    $handler = new UsersHandler();
    $result = $handler->toggleUserStatus($userID, $status);
    echo json_encode($result);
} else {
    echo json_encode([
        "success" => "No",
        "message" => "UserID and status are required"
    ]);
}
?>
