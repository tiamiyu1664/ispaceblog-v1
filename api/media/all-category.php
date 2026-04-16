<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); // For development, restrict in production
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include 'mediaHandler.php';
$category = new MediaHandler();
$all = $category->GetAllCategory();




?>