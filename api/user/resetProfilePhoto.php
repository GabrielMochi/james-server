<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once "../../config/database.php";
include_once "../../models/user.php";

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$uploadDir = "../../assets/user/profile";
$id = isset($_GET['id']) ? intval($_GET['id']) : die();

$user->id = $id;

if (isset($_SESSION["userId"]) && isset($_SESSION["userType"])) {
  if ($_SESSION["userId"] === $user->id || $_SESSION["userType"] === "ADMIN") {
    $hash = md5(strval($id));

    $paths = glob($uploadDir."/".$hash.".*");

    if (count($paths) > 0) {
      $profilePhotoPath = $paths[0];

      $user->profilePhoto = "/assets/user/profile/default_avatar.png";

      if ($user->editProfilePhoto()) {
        unlink($profilePhotoPath);
      
        http_response_code(200);
        echo json_encode($user->profilePhoto);
      } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to edit profile photo."));
      }
    } else {
      http_response_code(204);
      echo json_encode(array("message" => "No photo was found with id: ".$id."."));
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