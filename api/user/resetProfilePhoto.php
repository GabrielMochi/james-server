<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$uploadDir = "../../assets/user/profile";
$id = isset($_GET['id']) ? $_GET['id'] : die();
$hash = md5($id);

$paths = glob($uploadDir."/".$hash.".*");

if (count($paths) > 0) {
  $profilePhotoPath = $paths[0];

  unlink($profilePhotoPath);

  http_response_code(200);
  echo json_encode("/assets/user/profile/default_avatar.png");
} else {
  http_response_code(204);
  echo json_encode(array("message" => "No photo was found with id: ".$id."."));
}
?>