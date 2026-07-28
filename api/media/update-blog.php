<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

ini_set('display_errors', 0);
error_reporting(0);

include 'mediaHandler.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blogID = $_GET['id'] ?? $_POST['BlogID'] ?? '';
    $data = $_POST;

    $media = new MediaHandler();
    $result = $media->updateBlog($blogID, $data);

    echo json_encode($result);
} else {
    echo json_encode([
        "success" => "No",
        "message" => "Invalid Request Method"
    ]);
}
?>
