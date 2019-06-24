<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../models/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$user->id = isset($_GET['id']) ? intval($_GET['id']) : die();

if (isset($_SESSION["userId"]) && isset($_SESSION["userType"])) {
  if ($_SESSION["userId"] === $user->id || $_SESSION["userType"] === "ADMIN") {
    if ($user->deactivate()) {
      http_response_code(200);
      echo json_encode(array("message" => "The user was deactivated."));
    } else {
      http_response_code(503);
      echo json_encode(array("message" => "Unable to deactivate user."));
    }
  }  else {
    http_response_code(401);
    echo json_encode(array("message" => "Unauthorized."));
  }
} else {
  http_response_code(401);
  echo json_encode(array("message" => "Unauthorized."));
}
?>