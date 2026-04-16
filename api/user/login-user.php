<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); // For development, restrict in production
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include 'usersHandler.php';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $rawInput = file_get_contents("php://input");
    $data = json_decode($rawInput, true);
    if(empty($data)){
      $data = $_POST;
    }
    $user = new UsersHandler();
    $login = $user->LoginUser($data);
    $message = $login['message'];
    $response = $login['success'];
    $redirect = $login['redirect'];
    if($response == "Yes"){
        http_response_code(200);
        echo json_encode(["success"=>"Yes", "message"=>$message, "redirect"=>$redirect]);
    }else{
        http_response_code(400);
        echo json_encode(["success"=>"No", "message"=>$message, "redirect"=>$redirect]);
    }

}else{
    http_response_code(500);
    echo json_encode(["success"=>"No", "message"=>"Invalid Request Method", "redirect"=>'']);

}
?>