<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

if (session_destroy()) {
  http_response_code(200);
  echo json_encode(array("message" => "User was successfully logout."));
} else {
  http_response_code(503);
  echo json_encode(array("message" => "Unable to logout user."));
}
?>