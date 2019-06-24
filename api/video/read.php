<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../models/video.php';
include_once '../../models/user.php';

$database = new Database();
$db = $database->getConnection();

$video = new Video($db);

if (isset($_SESSION['userId'])) {
  $stmt = $video->read();
  
  $videosArr = array();
  
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    extract($row);
  
    $user = new User($db);
    $user->id = intval($userId);
    $user->readOne();
  
    $videoItem = array(
      "id" => intval($id),
      "title" => $title,
      "path" => $path,
      "thumbnailPhoto" => $thumbnailPhoto,
      "likes" => intval($likes),
      "dislikes" => intval($dislikes),
      "views" => intval($views),
      "user" => array(
        "id" => $user->id,
        "username" => $user->username,
        "firstname" => $user->firstname,
        "lastname" => $user->lastname,
        "email" => $user->email,
        "profilePhoto" => $user->profilePhoto,
        "type" => $user->type,
        "activate" => $user->activate
       )
    );
  
    array_push($videosArr, $videoItem);
  }
  
  http_response_code(200);
  echo json_encode($videosArr);
} else {
  http_response_code(401);
  echo json_encode(array("message" => "Unauthorized."));
}

?>