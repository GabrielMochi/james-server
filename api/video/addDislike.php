<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include_once '../../config/database.php';
include_once '../../models/video.php';

$database = new Database();
$db = $database->getConnection();

$video = new Video($db);

$video->id = isset($_GET['id']) ? intval($_GET['id']) : die();

if ($video->addDislike()) {
  http_response_code(200);
  echo json_encode($video->dislikes);
} else {
  http_response_code(503);
  echo json_encode(array("message" => "Unable to add dislike to the video."));
}
?>