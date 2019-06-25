<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../models/video.php';

$database = new Database();
$db = $database->getConnection();

$video = new Video($db);

$video->id = isset($_GET['id']) ? intval($_GET['id']) : die();

if (isset($_SESSION['userId'])) {
  $video->readOne();

  if ($video->title != null) {
    $videoArr = array(
      "id" => intval($video->id),
      "title" => $video->title,
      "path" => $video->path,
      "thumbnailPhoto" => $video->thumbnailPhoto,
      "likes" => intval($video->likes),
      "dislikes" => intval($video->dislikes),
      "views" => intval($video->views),
      "user" => array(
        "id" => intval($video->user->id),
        "username" => $video->user->username,
        "firstname" => $video->user->firstname,
        "lastname" => $video->user->lastname,
        "email" => $video->user->email,
        "profilePhoto" => $video->user->profilePhoto,
        "type" => $video->user->type,
        "activate" => $video->user->activate
       )
    );

    http_response_code(200);
    echo json_encode($videoArr);
  }  else {
    http_response_code(204);
    echo json_encode(array("message" => "Video does not exist."));
  }
} else {
  http_response_code(401);
  echo json_encode(array("message" => "Unauthorized."));
}
?>