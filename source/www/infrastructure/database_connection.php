<?php
require_once(dirname(__FILE__) . '/../../../config/db_config.php');
require_once(dirname(__FILE__) . '/log.php');

class DatabaseConnection {
  private $conn;
  
  public static $logfile = '/tmp/pom-sql-failures.log';
  
  private static $soleInstance;

  public static function load($db) {
    self::$soleInstance = $db;
  }
  
  public static function reset() {
    self::$soleInstance = null;
  }
  
  public static function instance() {
    if ( ! self::$soleInstance) {
      self::load(new DatabaseConnection());
    }
    return self::$soleInstance;
  }
  
  private function __construct() {
    $this->conn = new mysqli(DBConfig::HOST, DBConfig::USERNAME, DBConfig::PASSWORD, DBConfig::DATABASE);
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
    $this->log = new Log(self::$logfile);
    $this->echo = false;
    //$this->log2 = new Log('/tmp/pom-sql.log');
  }
  
  public function insert() {
    $args = func_get_args();
    call_user_func_array(array($this, "query"), $args);
    return $this->query("SELECT LAST_INSERT_ID() as id")->fetch_object()->id;
  }
  
  public function query_row() {
    $args = func_get_args();
    $result = call_user_func_array(array($this, "query"), $args);
    if (!$result) return null;
    return $result->fetch_object();
  }
  
  public function query_value() {
    $args = func_get_args();
    $result = call_user_func_array(array($this, "query"), $args);
    if (!$result) return null;
    $row = $result->fetch_assoc();
    if (!$row) return null;
    if (! isset($row['value'])) {
      $r = "";
      foreach ($row as $key => $value) {
        $r .= "$key => $value,";
      }
      throw new Exception("Found no value for: $this->last_sql, result was [$r]");
    }
    return $row['value'];
  }

  public function query_keyed_values() {
    $args = func_get_args();
    $result = call_user_func_array(array($this, "query"), $args);
    if (!$result) return null;
    $values = array();
    while ($row = $result->fetch_assoc()) {
      $values[$row['k']] = $row['v'];
    }
    return $values;
  }
  
  public function query() {
    $parameters = func_get_args();
    $sql = array_shift($parameters);
    foreach ($parameters as $parameter) {
      if (!is_numeric($parameter)) {
        $parameter = "'".$this->conn->real_escape_string(str_replace('\\', '\\\\', $parameter))."'";
        $parameter = str_replace("?", ":::[SPØRSMÅLSTEGN]:::", $parameter);
      }
      $sql = preg_replace('/\?/', $parameter, $sql, 1);
    }
    $sql = str_replace(":::[SPØRSMÅLSTEGN]:::", "?", $sql);
    if ($this->echo) {
      if (substr($sql, 0, 6) != "SELECT")
      echo "$sql</br>";
    }
    $result = $this->conn->query($sql);
    $this->last_sql = $sql;
    //$this->log2->message("$sql\n");
    if (!$result) {
      $this->log->message("SQL failed: $sql\n"); // TODO: Airbag her!
    }
    return $result;
  }
  
  public function __destruct() {
    if (isset($this->conn)) {
      try { $this->conn->close(); } catch (Exception $e) { /* ignore */ }
    }
  }
  
}
?>