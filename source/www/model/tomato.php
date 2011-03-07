<?php
require_once(dirname(__FILE__) . '/../infrastructure/database_connection.php');

class Tomato {
  private $id, $account_id, $status, $start_time, $end_time, $description, $server_start_time;

  public static function start($account_id, $start_time) {
    return new Tomato($account_id, "started", $start_time);
  }
  
  public function __construct($account_id, $status, $start_time, $end_time = null, $description = null) {
    $this->account_id = $account_id;
    $this->status = $status;
    $this->start_time = $start_time;
    $this->end_time = $end_time;
    $this->description = $description;
  }
  
  public function complete($end_time, $description) {
    $this->status = "completed";
    $this->end_time = $end_time;
    $this->description = $description;
  }

  public function squash($end_time) {
    $this->status = "squashed";
    $this->end_time = $end_time;
  }

  public function setId($id) {
    if ( ! $this->id) {
      $this->id = $id;
    } else {
      throw new Exception("ID already set");
    }
  }

  public function getId()          { return $this->id; }
  public function getAccountId()   { return $this->account_id; }
  public function getStatus()      { return $this->status; }
  public function getStartTime()   { return $this->start_time; }
  public function getEndTime()     { return $this->end_time; }
  public function getDescription() { return $this->description; }
  
  public function getEuropeanInterval() {
    return $this->clock($this->start_time)." - ".$this->clock($this->end_time);
  }
  
  public function getAmericanInterval() {
    $start = strtotime($this->start_time);
    $end = strtotime($this->end_time);
    return date("h:i A", $start)." - ".date("h:i A", $end);
  }

  public function getStartDate() {
    return substr($this->start_time, 0, 10);
  }

  private function clock($time) {
    return substr($time, 11, 5);
  }
  
}

class TomatoRepository {
  
  public function __construct() {
    
  }
  
  public function save($tomato) {
    $id = DatabaseConnection::instance()->insert("INSERT INTO Tomatoes (account_id, status, description, local_start, local_end, created_at) VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP)", $tomato->getAccountId(), $tomato->getStatus(), $tomato->getDescription(), $tomato->getStartTime(), $tomato->getEndTime());
    $tomato->setId($id);
  }
  
  public function byAccountId($account_id) {
    $tomatoes = array();
    $rs = DatabaseConnection::instance()->query("SELECT * FROM Tomatoes WHERE account_id = ? ORDER BY id DESC", $account_id);
    while ($row = $rs->fetch_assoc()) {
      $tomatoes[] = $this->createTomato($row);
    }
    return $tomatoes;
  }
  
  private function createTomato($row) {
    $tomato = new Tomato($row["account_id"], $row["status"], $row["local_start"], $row["local_end"], $row["description"]);
    $tomato->setId($row["id"]);
    return $tomato;
  }
  
}

?>