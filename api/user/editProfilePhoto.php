<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once "../../config/database.php";
include_once "../../models/user.php";

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$id = isset($_POST['id']) ? intval($_POST['id']) : die();
$hash = md5(strval($id));
$profilePhotoInfo = pathinfo($_FILES['profilePhoto']['name']);

$profilePhotoExtension = $profilePhotoInfo['extension'];
$profilePhotoPath = "../../assets/user/profile/".$hash.".".$profilePhotoExtension;
$staticProfilePhotoPath = "/assets/user/profile/".$hash.".".$profilePhotoExtension;

$user->id = $id;
$user->profilePhoto = $staticProfilePhotoPath;

if (isset($_SESSION["userId"]) && isset($_SESSION["userType"])) {
  if ($_SESSION["userId"] === $user->id || $_SESSION["userType"] === "ADMIN") {
    if ($user->editProfilePhoto()) {
      move_uploaded_file($_FILES['profilePhoto']['tmp_name'], $profilePhotoPath);
    
      http_response_code(200);
      echo json_encode($staticProfilePhotoPath);
    } else {
      http_response_code(503);
      echo json_encode(array("message" => "Unable to edit profile photo."));
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