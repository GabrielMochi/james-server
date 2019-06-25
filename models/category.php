<?php
class Category {

  private $conn;
  private $tableName = "category";

  public $id;
  public $name;

  public function __construct ($db) {
    $this->conn = $db;
  }

  function read () {
    $query = "SELECT * FROM ".$this->tableName;

    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    return $stmt;
  }

  function readOneById () {
    $query = "SELECT * FROM ".$this->tableName."c WHERE c.id = ? LIMIT 0,1";

    $stmt = $this->conn->prepare($query);

    $this->id = htmlspecialchars(strip_tags($this->id));

    $stmt->bindParam(1, $this->id);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $this->id = intval($row['id']);
    $this->name = $row['name'];
  }

  function readOneByName () {
    $query = "SELECT * FROM ".$this->tableName."c WHERE c.name = ? LIMIT 0,1";

    $stmt = $this->conn->prepare($query);

    $this->name = htmlspecialchars(strip_tags($this->name));

    $stmt->bindParam(1, $this->name, PDO::PARAM_STR);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $this->id = intval($row['id']);
    $this->name = $row['name'];
  }

  function create () {
    $query = "INSERT INTO ".$this->tableName. " (
      name
    ) VALUES (
      :name
    )";

    $stmt = $this->conn->prepare($query);

    $this->name = htmlspecialchars(strip_tags($this->name));

    $stmt->bindParam(":name", $this->name, PDO::PARAM_STR);

    $stmt->execute();
    
    $this->id = intval($this->conn->lastInsertId());
  }

}
?>