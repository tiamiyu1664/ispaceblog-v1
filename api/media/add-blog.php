<?php 



header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); // restrict in production
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");



// PROD
ini_set('display_errors', 0);
error_reporting(0);

include 'mediaHandler.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ✅ Since you're using FormData (multipart/form-data), use $_POST directly
    $data = $_POST;

    $media = new MediaHandler();

    // Optional debug log
    // $media->Errorlog($data);

    $register = $media->addBlog($data);

    // ✅ Safe defaults (prevents "Undefined index" notice)
    $success  = $register['success']  ?? "No";
    $message  = $register['message']  ?? "Unknown error";
    $redirect = $register['redirect'] ?? "";

    // ✅ Always return valid JSON
    echo json_encode([
        "success"  => $success,
        "message"  => $message,
        "redirect" => $redirect
    ]);

} else {

    echo json_encode([
        "success"  => "No",
        "message"  => "Invalid Request Method",
        "redirect" => ""
    ]);
}


?>