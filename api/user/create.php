<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: *");

include_once '../../config/database.php';
include_once '../../models/user.php';
 
$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

if (
  !empty($data->username) &&
  !empty($data->firstname) &&
  !empty($data->lastname) &&
  !empty($data->email) &&
  !empty($data->password)
) {
  $user->username = $data->username;
  $user->firstname = $data->firstname;
  $user->lastname = $data->lastname;
  $user->email = $data->email;
  $user->password = $data->password;

  $user->create();

  if ($user->id != null) {
    $_SESSION["userId"] = $user->id;
    $_SESSION["userType"] = "USER"; // all created users are set as USER as default.

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
    http_response_code(503);
    echo json_encode(array("message" => "Unable to create user."));
  }
} else {
  http_response_code(400);
  echo json_encode(array("message" => "Unable to create user. Data is incomplete."));
}
?>