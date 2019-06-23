<?php
class Database {

  private $host = "localhost";
  private $dbName = "james";
  private $username = "root";
  private $password = "";

  public function getConnection () {
    $this->conn = null;

    try {
      $this->conn = new PDO("mysql:host=".$this->host.";dbname=".$this->dbName, $this->username, $this->password);
      $this->conn->exec("set names utf8");


    } catch (PDOException $exception) {
      echo "MySQL connection error: ".$exception->getMessage();
    }

    return $this->conn;
  }

}
?>