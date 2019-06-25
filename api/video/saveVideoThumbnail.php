<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: *");

if (isset($_FILES['thumbnailPhoto'])) {
  $hash = md5(uniqid(rand(), true));
  $thumbnailPhotoInfo = pathinfo($_FILES['thumbnailPhoto']['name']);

  $thumbnailPhotoExtension = $thumbnailPhotoInfo['extension'];
  $thumbnailPhotoPath = "../../assets/video/thumbnail/".$hash.".".$thumbnailPhotoExtension;
  $staticThumbnailPhotoPath = "/assets/video/thumbnail/".$hash.".".$thumbnailPhotoExtension;
  
  move_uploaded_file($_FILES['thumbnailPhoto']['tmp_name'], $thumbnailPhotoPath);
      
  http_response_code(200);
  echo json_encode($staticThumbnailPhotoPath);
} else {
  http_response_code(400);
  echo json_encode(array("message" => "Missing param 'thumbnailPhoto' in data."));
}
?>