<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

include 'usersHandler.php';

$handler = new UsersHandler();
$result = $handler->getAllUsers();
echo json_encode($result);
?>
