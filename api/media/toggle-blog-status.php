<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type");

include 'mediaHandler.php';

$blogID = $_GET['id'] ?? $_POST['BlogID'] ?? '';
$status = $_GET['status'] ?? $_POST['StatusID'] ?? '';

if (!empty($blogID) && !empty($status)) {
    $media = new MediaHandler();
    $result = $media->toggleBlogStatus($blogID, $status);
    echo json_encode($result);
} else {
    echo json_encode([
        "success" => "No",
        "message" => "BlogID and status are required"
    ]);
}
?>
