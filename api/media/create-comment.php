<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include 'mediaHandler.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rawInput = file_get_contents("php://input");
    $data = json_decode($rawInput, true) ?? $_POST;

    $media = new MediaHandler();
    $result = $media->addComment($data);
    echo json_encode($result);
} else {
    echo json_encode([
        "success" => "No",
        "message" => "Invalid Request Method"
    ]);
}
?>
