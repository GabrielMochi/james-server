<?php
include_once "user.php";
include_once "video.php";
include_once "category.php";

class VideoHasCategory {

  private $conn;
  private $tableName = "videoHasCategory";

  public function __construct ($db) {
    $this->conn = $db;
  }

}
?>