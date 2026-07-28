<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type");

include 'mediaHandler.php';

$commentID = $_GET['id'] ?? $_POST['CommentID'] ?? '';

if (!empty($commentID)) {
    $media = new MediaHandler();
    $result = $media->updateCommentStatus($commentID, 'A');
    echo json_encode($result);
} else {
    echo json_encode([
        "success" => "No",
        "message" => "CommentID is required"
    ]);
}
?>
