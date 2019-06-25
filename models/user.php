<?php
class User {

  private $conn;
  private $tableName = "user";

  public $id;
  public $username;
  public $firstname;
  public $lastname;
  public $email;
  public $password;
  public $profilePhoto;
  public $type;
  public $activate;

  public function __construct ($db) {
    $this->conn = $db;
  }

  function read () {
    $query = "SELECT
      id, username, firstname,
      lastname, email, profilePhoto,
      type, activate
    FROM ".$this->tableName;

    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    return $stmt;
  }

  function create () {
    $query = "INSERT INTO ".$this->tableName." (
      username, firstname,
      lastname, email, password
    ) VALUES (
      :username,
      :firstname,
      :lastname,
      :email,
      :password
    )";

    $stmt = $this->conn->prepare($query);

    $this->username = htmlspecialchars(strip_tags($this->username));
    $this->firstname = htmlspecialchars(strip_tags($this->firstname));
    $this->lastname = htmlspecialchars(strip_tags($this->lastname));
    $this->email = htmlspecialchars(strip_tags($this->email));
    $this->password = htmlspecialchars(strip_tags($this->password));

    $stmt->bindParam(":username", $this->username, PDO::PARAM_STR);
    $stmt->bindParam(":firstname", $this->firstname, PDO::PARAM_STR);
    $stmt->bindParam(":lastname", $this->lastname, PDO::PARAM_STR);
    $stmt->bindParam(":email", $this->email, PDO::PARAM_STR);
    $stmt->bindParam(":password", $this->password, PDO::PARAM_STR);

    $stmt->execute();

    $this->id = intval($this->conn->lastInsertId());
    
    $this->readOne();
  }

  function readOne () {
    $query = "SELECT
      id, username, firstname,
      lastname, email, profilePhoto,
      type, activate
    FROM ".$this->tableName." u
    WHERE u.id = ? LIMIT 0,1";
    
    $stmt = $this->conn->prepare($query);

    $this->id = htmlspecialchars(strip_tags($this->id));

    $stmt->bindParam(1, $this->id, PDO::PARAM_INT);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $this->id = intval($row['id']);
    $this->username = $row['username'];
    $this->firstname = $row['firstname'];
    $this->lastname = $row['lastname'];
    $this->email = $row['email'];
    $this->profilePhoto = $row['profilePhoto'];
    $this->type = $row['type'];
    $this->activate = (intval($row['activate']) === 1);
  }

  function update () {
    $query = "UPDATE
      ".$this->tableName."
    SET
      username = :username,
      firstname = :firstname,
      lastname = :lastname,
      email = :email,
      profilePhoto = :profilePhoto,
      type = :type,
      activate = :activate
    WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $this->id = htmlspecialchars(strip_tags($this->id));
    $this->username = htmlspecialchars(strip_tags($this->username));
    $this->firstname = htmlspecialchars(strip_tags($this->firstname));
    $this->lastname = htmlspecialchars(strip_tags($this->lastname));
    $this->email = htmlspecialchars(strip_tags($this->email));
    $this->profilePhoto = htmlspecialchars(strip_tags($this->profilePhoto));
    $this->type = htmlspecialchars(strip_tags($this->type));
    $this->activate = htmlspecialchars(strip_tags($this->activate));

    $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
    $stmt->bindParam(':username', $this->username, PDO::PARAM_STR);
    $stmt->bindParam(':firstname', $this->firstname, PDO::PARAM_STR);
    $stmt->bindParam(':lastname', $this->lastname, PDO::PARAM_STR);
    $stmt->bindParam(':email', $this->email, PDO::PARAM_STR);
    $stmt->bindParam(':profilePhoto', $this->profilePhoto, PDO::PARAM_STR);
    $stmt->bindParam(':type', $this->type, PDO::PARAM_STR);
    $stmt->bindParam(':activate', $this->activate, PDO::PARAM_INT);

    return $stmt->execute();
  }

  function activate () {
    $query = "UPDATE
      ".$this->tableName."
    SET
      activate = 1
    WHERE id = ?";

    $stmt = $this->conn->prepare($query);

    $this->id = htmlspecialchars(strip_tags($this->id));

    $stmt->bindParam(1, $this->id, PDO::PARAM_INT);

    return $stmt->execute();
  }

  function deactivate () {
    $query = "UPDATE
      ".$this->tableName."
    SET
      activate = 0
    WHERE id = ?";

    $stmt = $this->conn->prepare($query);

    $this->id = htmlspecialchars(strip_tags($this->id));

    $stmt->bindParam(1, $this->id, PDO::PARAM_INT);

    return $stmt->execute();
  }

  function setAsUser () {
    $query = "UPDATE
      ".$this->tableName."
    SET
      type = 'USER'
    WHERE id = ?";

    $stmt = $this->conn->prepare($query);

    $this->id = htmlspecialchars(strip_tags($this->id));

    $stmt->bindParam(1, $this->id, PDO::PARAM_INT);

    return $stmt->execute();
  }

  function setAsAdmin () {
    $query = "UPDATE
      ".$this->tableName."
    SET
      type = 'ADMIN'
    WHERE id = ?";

    $stmt = $this->conn->prepare($query);

    $this->id = htmlspecialchars(strip_tags($this->id));

    $stmt->bindParam(1, $this->id, PDO::PARAM_INT);

    return $stmt->execute();
  }

  function login () {
    $query = "SELECT
      id, username, firstname,
      lastname, email, profilePhoto,
      type, activate
    FROM ".$this->tableName." u
    WHERE
      u.username = :username AND
      u.password = :password
    LIMIT 0,1";
    
    $stmt = $this->conn->prepare($query);

    $this->username = htmlspecialchars(strip_tags($this->username));
    $this->password = htmlspecialchars(strip_tags($this->password));

    $stmt->bindParam(':username', $this->username, PDO::PARAM_STR);
    $stmt->bindParam(':password', $this->password, PDO::PARAM_STR);

    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $this->id = intval($row['id']);
    $this->username = $row['username'];
    $this->firstname = $row['firstname'];
    $this->lastname = $row['lastname'];
    $this->email = $row['email'];
    $this->profilePhoto = $row['profilePhoto'];
    $this->type = $row['type'];
    $this->activate = (intval($row['activate']) === 1);
  }

  function editProfilePhoto () {
    $query = "UPDATE
      ".$this->tableName."
    SET
      profilePhoto = :profilePhoto
    WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $this->id = htmlspecialchars(strip_tags($this->id));
    $this->profilePhoto = htmlspecialchars(strip_tags($this->profilePhoto));

    $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
    $stmt->bindParam(':profilePhoto', $this->profilePhoto, PDO::PARAM_STR);

    return $stmt->execute();
  }

}
?>