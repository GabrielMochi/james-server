<?php
include_once "user.php";

class Video {

  private $conn;
  private $tableName = "video";

  public $id;
  public $title;
  public $path;
  public $thumbnailPhoto;
  public $likes;
  public $dislikes;
  public $views;
  public $user; // foreign key

  public function __construct ($db) {
    $this->conn = $db;
  }

  function read () {
    $query = "SELECT * FROM ".$this->tableName;
    
    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    return $stmt;
  }

  function create () {
    $query = "INSERT INTO ".$this->tableName." (
      title, path, thumbnailPhoto, userId
    ) VALUES (
      :title,
      :path,
      :thumbnailPhoto,
      :userId
    )";

    $stmt = $this->conn->prepare($query);

    $this->title = htmlspecialchars(strip_tags($this->title));
    $this->path = htmlspecialchars(strip_tags($this->path));
    $this->thumbnailPhoto = htmlspecialchars(strip_tags($this->thumbnailPhoto));
    $this->user->id = htmlspecialchars(strip_tags($this->user->id));

    $stmt->bindParam(":title", $this->title, PDO::PARAM_STR);
    $stmt->bindParam(":path", $this->path, PDO::PARAM_STR);
    $stmt->bindParam(":thumbnailPhoto", $this->thumbnailPhoto, PDO::PARAM_STR);
    $stmt->bindParam(":userId", $this->user->id, PDO::PARAM_INT);

    $stmt->execute();

    $this->id = intval($this->conn->lastInsertId());

    $this->readOne();
  }

  function readOne () {
    $query = "SELECT * FROM ".$this->tableName." v WHERE v.id = ? LIMIT 0,1";

    $stmt = $this->conn->prepare($query);

    $this->id = htmlspecialchars(strip_tags($this->id));

    $stmt->bindParam(1, $this->id, PDO::PARAM_INT);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $this->id = intval($row['id']);
    $this->title = $row['title'];
    $this->path = $row['path'];
    $this->thumbnailPhoto = $row['thumbnailPhoto'];
    $this->likes = intval($row['likes']);
    $this->dislikes = intval($row['dislikes']);
    $this->views = intval($row['views']);

    $user = new User($this->conn);

    $user->id = intval($row['userId']);

    $user->readOne();

    $this->user = $user;

    $this->addOneView();
  }

  function update () {
    $query = "UPDATE
      ".$this->tableName."
    SET
      title = :title
      path = :path
      thumbnailPhoto = :thumbnailPhoto
      likes = :likes
      dislikes = :dislikes
      views = :views
      userId = :userId
    WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $this->id = htmlspecialchars(strip_tags($this->id));
    $this->title = htmlspecialchars(strip_tags($this->title));
    $this->path = htmlspecialchars(strip_tags($this->path));
    $this->thumbnailPhoto = htmlspecialchars(strip_tags($this->thumbnailPhoto));
    $this->likes = htmlspecialchars(strip_tags($this->likes));
    $this->dislikes = htmlspecialchars(strip_tags($this->dislikes));
    $this->views = htmlspecialchars(strip_tags($this->views));
    $this->user->id = htmlspecialchars(strip_tags($this->user->id));

    $stmt->bindParam(':id', $this->id, PDO::PARAM_STR);
    $stmt->bindParam(':title', $this->title, PDO::PARAM_STR);
    $stmt->bindParam(':path', $this->path, PDO::PARAM_STR);
    $stmt->bindParam(':thumbnailPhoto', $this->thumbnailPhoto, PDO::PARAM_STR);
    $stmt->bindParam(':likes', $this->likes, PDO::PARAM_INT);
    $stmt->bindParam(':dislikes', $this->dislikes, PDO::PARAM_INT);
    $stmt->bindParam(':views', $this->views, PDO::PARAM_INT);
    $stmt->bindParam(':userId', $this->user->id, PDO::PARAM_INT);

    return $stmt->execute();
  }

  function delete () {
    $query = "DELETE FROM ".$this->tableName." WHERE id = ?";

    $stmt = $this->conn->prepare($query);

    $this->id=htmlspecialchars(strip_tags($this->id));

    $stmt->bindParam(1, $this->id);

    return $stmt->execute();
  }

  function addLike () {
    $this->readOne();

    $query = "UPDATE
      ".$this->tableName."
    SET
      likes = :likes
    WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $this->likes++;

    $stmt->bindParam(':likes', $this->likes, PDO::PARAM_INT);
    $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

    return $stmt->execute();
  }

  function addDislike () {
    $this->readOne();

    $query = "UPDATE
      ".$this->tableName."
    SET
      dislikes = :dislikes
    WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $this->dislikes++;

    $stmt->bindParam(':dislikes', $this->dislikes, PDO::PARAM_INT);
    $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

    return $stmt->execute();
  }

  private function addOneView () {
    $query = "UPDATE
      ".$this->tableName."
    SET
      views = :views
    WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $this->views++;

    $stmt->bindParam(':views', $this->views, PDO::PARAM_INT);
    $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

    $stmt->execute();
  }

}
?>