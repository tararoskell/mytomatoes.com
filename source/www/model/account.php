<?php
require_once(dirname(__FILE__) . '/../infrastructure/database_connection.php');

class Account {
  
  public function __construct($username, $hashed_password, $random_salt) {
    $this->username = $username;
    $this->hashed_password = $hashed_password;
    $this->random_salt = $random_salt;
    $this->id = null;
    $this->preferences = null;
  }
  
  public function setId($id) {
    if ( ! $this->id) {
      $this->id = $id;
    } else {
      throw new Exception("ID already set");
    }
  }
  
  public function setPreferences($preferences) {
    $this->preferences = $preferences;
  }
  
  public function getPreference($name) {
    return $this->preferences->get($name);
  }
  
  public function getPreferences() {
    return $this->preferences->all();
  }
  
  public function setPreference($name, $value) {
    $this->preferences->set($name, $value);
  }

  public function getId()             { return $this->id; }
  public function getUsername()       { return $this->username; }
  public function getHashedPassword() { return $this->hashed_password; }
  public function getRandomSalt()     { return $this->random_salt; }

  public function matchPassword($password) {
    return $this->hashed_password === self::hash_password($password, $this->random_salt);
  }

  public static function hash_password($password, $random_salt) {
    $static_salt = "*k*Pn9OR, ab5ec025e85ab1ab0de4bcab4b70068b4b3642fe, 062b1030-e63c-4f16-96bc-fd38dee78ae6OwBDZefhqlbYZ-wiIm+/N81l)V_(q-a5xD0IL4fzAFiRaxv9M39e87N_O*tog9+u, de5e6b220220759326851bc49cde941e576e9114, 18287807-d501-4c4c-9a13-cbcff7b75ee8(seRpitb!P=eSOCvd7@gbfH!c6oROD#OqRb7**EnBtlZn24fhzQp(U*(";
    return hash("sha256", "$password+$static_salt+$random_salt");
  }

  public static function create($username, $password) {
    $random_salt = time();
    $hashed_password = self::hash_password($password, $random_salt);
    $account = new Account($username, $hashed_password, $random_salt);
    return $account;
  }
  
}

class AccountRepository {
  
  public function __construct() {
    
  }
  
  public function existsWithUsername($username) {
    return DatabaseConnection::instance()->query_value("SELECT count(*) as value FROM Accounts WHERE username = ?", $username);
  }
  
  public function save($account) {
    $id = DatabaseConnection::instance()->insert("INSERT INTO Accounts (username, hashed_password, random_salt, created_at) VALUES (?, ?, ?, CURRENT_TIMESTAMP)", $account->getUsername(), $account->getHashedPassword(), $account->getRandomSalt());
    $account->setId($id);
    $account->setPreferences(new LazyPreferences($id));
  }
  
  public function byId($id) {
    return $this->account_where("id = ?", $id);
  }
  
  public function byUsername($username) {
    return $this->account_where("username = ?", $username);
  }
  
  private function account_where($where, $param) {
    $row = DatabaseConnection::instance()->query_row("SELECT id, username, hashed_password, random_salt FROM Accounts WHERE $where", $param);
    if ( ! $row) {
      return null;
    }
    $account = new Account($row->username, $row->hashed_password, $row->random_salt);
    $account->setId($row->id);
    $account->setPreferences(new LazyPreferences($row->id));
    return $account;
  }
  
}

class LazyPreferences {
  
  public function __construct($id) {
    $this->id = $id;
    $this->instance = null;
  }
  
  public function all()               { return $this->preferences()->all(); }
  public function get($name)          { return $this->preferences()->get($name); }
  public function set($name, $value)  { $this->preferences()->set($name, $value); }
  
  private function preferences() {
    if ( ! $this->instance) {
      $this->instance = new Preferences($this->id);
    }
    return $this->instance;
  }
  
}


class Preferences {
  
  public function __construct($id) {
    $this->id = $id;
    $this->preferences = DatabaseConnection::instance()->query_keyed_values("SELECT name as k, value as v FROM Preferences WHERE account_id = ?", $this->id);
  }

  public function all() {
    return $this->preferences;
  }
  
  public function get($name) {
    return isset($this->preferences[$name]) ? $this->preferences[$name] : null;
  }
  
  public function set($name, $value) {
    if (isset($this->preferences[$name])) {
      DatabaseConnection::instance()->query("UPDATE Preferences SET value = ? WHERE account_id = ? AND name = ?", $value, $this->id, $name);
    } else {
      DatabaseConnection::instance()->query("INSERT INTO Preferences (account_id, name, value) VALUES (?, ?, ?)", $this->id, $name, $value);
    }
    $this->preferences[$name] = $value;
  }
  
}

?>