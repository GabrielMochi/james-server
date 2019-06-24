<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$id = isset($_POST['id']) ? $_POST['id'] : die();
$hash = md5($id);
$profilePhotoInfo = pathinfo($_FILES['profilePhoto']['name']);

$profilePhotoExtension = $profilePhotoInfo['extension'];
$profilePhotoPath = "../../assets/user/profile/".$hash.".".$profilePhotoExtension;
$staticProfilePhotoPath = "/assets/user/profile/".$hash.".".$profilePhotoExtension;

move_uploaded_file($_FILES['profilePhoto']['tmp_name'], $profilePhotoPath);

http_response_code(200);
echo json_encode($staticProfilePhotoPath);
?>