<?php

$endpoint = $_GET['endpoint']; // change this value to test LoginUser  CreateCategory
// error_log(json_encode("got hereeee"), 3, __DIR__ . '/LOG_File1.txt');
switch ($endpoint) {
    case "registerUser":
        include_once "user/add-user.php";
        break;
    case "LoginUser":
        include_once "user/login-user.php";
        break;
    case "CreateCategory":
        include_once "media/create-category.php";
        break;
    case "GetAllCategory":
        include_once "media/all-category.php";
        break;
    case "CreateBlog":
        include_once "media/add-blog.php";
        //  error_log('Got here', 3, __DIR__ . '/LOG_File1.txt');
        break;
   

    default:
        echo json_encode(
            [
            "success"=>"No","message"=> "Invalid Requestss"
        ]);
        break;
}
