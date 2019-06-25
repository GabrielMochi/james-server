<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: *");

include_once '../../config/database.php';
include_once '../../models/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

$user->id = intval($data->id);
$user->username = $data->username;
$user->firstname = $data->firstname;
$user->lastname = $data->lastname;
$user->email = $data->email;
$user->profilePhoto = $data->profilePhoto;
$user->type = $data->type;
$user->activate = $data->activate ? 1 : 0;

if (isset($_SESSION["userId"]) && isset($_SESSION["userType"])) {
  if ($_SESSION["userId"] === $user->id || $_SESSION["userType"] === "ADMIN") {
    if ($user->update()) {
      http_response_code(200);
      echo json_encode(array("message" => "User was updated."));
    } else {
      http_response_code(503);
      echo json_encode(array("message" => "Unable to update user."));
    }
  } else {
    http_response_code(401);
    echo json_encode(array("message" => "Unauthorized."));
  }
} else {
  http_response_code(401);
  echo json_encode(array("message" => "Unauthorized."));
}
?>