<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../models/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$stmt = $user->read();

$usersArr = array();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  extract($row);

  $userItem = array(
    "id" => intval($id),
    "username" => $username,
    "firstname" => $firstname,
    "lastname" => $lastname,
    "email" => $email,
    "profilePhoto" => $profilePhoto,
    "type" => $type,
    "activate" => (intval($activate) === 1)
  );

  array_push($usersArr, $userItem);
}

http_response_code(200);
echo json_encode($usersArr);
?>