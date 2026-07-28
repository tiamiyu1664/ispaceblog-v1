<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type");

include 'mediaHandler.php';

$blogID = $_GET['id'] ?? $_POST['BlogID'] ?? '';

if (!empty($blogID)) {
    $media = new MediaHandler();
    $result = $media->deleteBlog($blogID);
    echo json_encode($result);
} else {
    echo json_encode([
        "success" => "No",
        "message" => "BlogID is required"
    ]);
}
?>
