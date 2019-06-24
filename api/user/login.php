<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../models/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->username) && !empty($data->password)) {
  $user->username = $data->username;
  $user->password = $data->password;

  $user->login();

  if ($user->id != null) {
    $_SESSION["userId"] = $user->id;
    $_SESSION["userType"] = $user->type;

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
    http_response_code(401);
    echo json_encode(array("message" => "The username or password is invalid."));
  }
} else {
  http_response_code(400);
  echo json_encode(array("message" => "Unable to login. Data is incomplete."));
}
?>