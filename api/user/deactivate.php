<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../models/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$user->id = isset($_GET['id']) ? intval($_GET['id']) : die();

if ($user->deactivate()) {
  http_response_code(200);
  echo json_encode(array("message" => "The user was deactivated."));
} else {
  http_response_code(503);
  echo json_encode(array("message" => "Unable to deactivate user."));
}
?>