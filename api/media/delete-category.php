<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type");

include 'mediaHandler.php';

$categoryID = $_GET['id'] ?? $_POST['CategoryID'] ?? '';

if (!empty($categoryID)) {
    $media = new MediaHandler();
    $result = $media->deleteCategory($categoryID);
    echo json_encode($result);
} else {
    echo json_encode([
        "success" => "No",
        "message" => "CategoryID is required"
    ]);
}
?>
