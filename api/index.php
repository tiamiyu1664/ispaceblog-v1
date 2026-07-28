<?php

$endpoint = $_GET['endpoint']; // change this value to test LoginUser  CreateCategory
// error_log(json_encode("got hereeee"), 3, __DIR__ . '/LOG_File1.txt');
switch ($endpoint) {
    case "registerUser":
        include_once "user/add-user.php";
        break;
    case "LoginAdminUser":
        include_once "user/login-user.php";
        break;
    case "LoginBlogUser":
        include_once "user/login-blog.php";
        break;
    case "CreateCategory":
        include_once "media/create-category.php";
        break;
    case "GetAllCategory":
        include_once "media/all-category.php";
        break;
    case "CreateBlog":
        include_once "media/add-blog.php";
        break;
    case "GetBlogs":
        include_once "media/get-blogs.php";
        break;
    case "UpdateBlog":
        include_once "media/update-blog.php";
        break;
    case "DeleteBlog":
        include_once "media/delete-blog.php";
        break;
    case "ToggleBlogStatus":
        include_once "media/toggle-blog-status.php";
        break;
    case "UpdateCategory":
        include_once "media/update-category.php";
        break;
    case "DeleteCategory":
        include_once "media/delete-category.php";
        break;
    case "CreateComment":
        include_once "media/create-comment.php";
        break;
    case "ApproveComment":
        include_once "media/approve-comment.php";
        break;
    case "RejectComment":
        include_once "media/reject-comment.php";
        break;
    case "DeleteComment":
        include_once "media/delete-comment.php";
        break;
    case "GetAllUsers":
        include_once "user/get-all-users.php";
        break;
    case "GetAllAdmins":
        include_once "user/get-all-admins.php";
        break;
    case "ToggleUserStatus":
        include_once "user/toggle-user-status.php";
        break;
    case "AddAdmin":
        include_once "user/add-admin.php";
        break;
    case "DeleteUser":
        include_once "user/delete-user.php";
        break;

    default:
        echo json_encode(
            [
            "success"=>"No","message"=> "Invalid Requestss"
        ]);
        break;
}
