<?php 
// header("Content-Type: application/json");
// header("Access-Control-Allow-Origin: *"); // For development, restrict in production
// header("Access-Control-Allow-Methods: POST");
// header("Access-Control-Allow-Headers: Content-Type");

// include 'mediaHandler.php';
// if($_SERVER['REQUEST_METHOD'] == 'POST'){
//     $rawInput = file_get_contents("php://input");
   
//     $data = json_decode($rawInput, true);
//     if(empty($data)){
//        $data = $_POST;
//     }

//     $category = new MediaHandler();
//     $category->Errorlog($data);
    
//     $register = $category->addBlog($data);
//     $message = $register['message'];
//     $response = $register['success'];
//     $redirect = $register['redirect'];
//     if($response == "Yes"){
         
//         http_response_code(200);
//         echo json_encode(["success"=>"Yes", "message"=>$message, "redirect"=>$redirect]);
//     }else{
//         http_response_code(400);
//         echo json_encode(["success"=>"No", "message"=>$message, "redirect"=>$redirect]);
//     }


// }else{
//     http_response_code(500);
//         echo json_encode(["success"=>"No", "message"=>"Invalid Request Method", "redirect"=>'']);

// }


header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); // restrict in production
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// DEBUG (turn off in production)
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

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