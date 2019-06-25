<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: *");

include_once '../../config/database.php';
include_once '../../models/video.php';
include_once '../../models/category.php';
include_once '../../models/videoHasCategory.php';
 
$database = new Database();
$db = $database->getConnection();

$video = new Video($db);

$data = json_decode(file_get_contents("php://input"));

if (
  !empty($data->title) &&
  !empty($data->path) &&
  !empty($data->thumbnailPhoto) &&
  !empty($data->user) &&
  !empty($data->categories)
) {
  $video->title = $data->title;
  $video->path = $data->path;
  $video->thumbnailPhoto = $data->thumbnailPhoto;
  $video->user = $data->user;

  $video->create();

  if ($video->id != null) {
    foreach ($data->categories as $categoryName) {
      $categoryName = strtolower($categoryName);

      $category = new Category($db);

      $category->name = $categoryName;

      $category->readOneByName();

      if ($category->id != null) {
        if (!createRelation($db, $video, $category)) {
          http_response_code(503);
          echo json_encode(array("message" => "Unable to create video."));
          return;
        }
      } else {
        $category->name = $categoryName;
        $category->create();
        
        if (!createRelation($db, $video, $category)) {
          http_response_code(503);
          echo json_encode(array("message" => "Unable to create video."));
          return;
        }
      }
    }

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
  } else {
    http_response_code(503);
    echo json_encode(array("message" => "Unable to create video."));
  }
} else {
  http_response_code(400);
  echo json_encode(array("message" => "Unable to create video. Data is incomplete."));
}

function createRelation ($db, $video, $category) {
  $videoHasCategory = new VideoHasCategory($db);

  $videoHasCategory->video = $video;
  $videoHasCategory->category = $category;
  $videoHasCategory->videoUser = $video->user;

  return $videoHasCategory->create();
}
?>