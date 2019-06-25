<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: *");

if (isset($_FILES['videoData'])) {
  $hash = md5(uniqid(rand(), true));
  $videoDataInfo = pathinfo($_FILES['videoData']['name']);

  $videoDataExtension = $videoDataInfo['extension'];
  $videoDataPath = "../../assets/video/data/".$hash.".".$videoDataExtension;
  $staticVideoDataPath = "/assets/video/data/".$hash.".".$videoDataExtension;

  if (isset($_SESSION["userId"])) {
    move_uploaded_file($_FILES['videoData']['tmp_name'], $videoDataPath);
      
    http_response_code(200);
    echo json_encode($staticVideoDataPath);
  } else {
    http_response_code(401);
    echo json_encode(array("message" => "Unauthorized."));
  }
} else {
  http_response_code(400);
  echo json_encode(array("message" => "Missing param 'videoData' in data."));
}
?>