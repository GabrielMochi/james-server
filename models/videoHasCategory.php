<?php
class VideoHasCategory {

  private $conn;
  private $tableName = "videoHasCategory";

  public $video;
  public $category;
  public $videoUser;

  public function __construct ($db) {
    $this->conn = $db;
  }

  function create () {
    $query = "INSERT INTO ".$this->tableName." (
      videoId, categoryId, videoUserId
    ) VALUES (
      :videoId,
      :categoryId,
      :videoUserId
    )";

    $stmt = $this->conn->prepare($query);

    $this->video->id = htmlspecialchars(strip_tags($this->video->id));
    $this->category->id = htmlspecialchars(strip_tags($this->category->id));
    $this->videoUser->id = htmlspecialchars(strip_tags($this->videoUser->id));

    $stmt->bindParam(':videoId', $this->video->id, PDO::PARAM_INT);
    $stmt->bindParam(':categoryId', $this->category->id, PDO::PARAM_INT);
    $stmt->bindParam(':videoUserId', $this->videoUser->id, PDO::PARAM_INT);

    return $stmt->execute();
  }

}
?>