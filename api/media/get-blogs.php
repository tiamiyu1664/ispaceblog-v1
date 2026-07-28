<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

include_once 'mediaHandler.php';

$category = $_GET['category'] ?? $_POST['category'] ?? '';
$search = $_GET['search'] ?? $_POST['search'] ?? '';

$media = new MediaHandler();
$result = $media->getBlogs($category, $search);

echo json_encode($result);
?>
