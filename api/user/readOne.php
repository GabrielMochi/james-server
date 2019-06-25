<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include_once '../../config/database.php';
include_once '../../models/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$user->id = isset($_GET['id']) ? intval($_GET['id']) : die();

if (isset($_SESSION["userId"]) && isset($_SESSION["userType"])) {
  if ($_SESSION["userId"] === $user->id || $_SESSION["userType"] === "ADMIN") {
    $user->readOne();

    if ($user->username != null) {
      $userArr = array(
        "id" => $user->id,
        "username" => $user->username,
        "firstname" => $user->firstname,
        "lastname" => $user->lastname,
        "email" => $user->email,
        "profilePhoto" => $user->profilePhoto,
        "type" => $user->type,
        "activate" => $user->activate
      );

      http_response_code(200);
      echo json_encode($userArr);
    } else {
      http_response_code(204);
      echo json_encode(array("message" => "User does not exist."));
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