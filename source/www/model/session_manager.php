<?php
require_once(dirname(__FILE__) . '/../model/account.php');
require_once(dirname(__FILE__) . '/../infrastructure/log_events.php');
require_once(dirname(__FILE__) . '/../infrastructure/database_connection.php');

class SessionManager {
  
  private static $soleInstance;
  
  public static function load($sm) {
    self::$soleInstance = $sm;
  }
  
  public static function current() {
    if ( ! self::$soleInstance) {
      self::load(new SessionManager());
    }
    return self::$soleInstance;
  }
  
  private function __construct() {
    if (! isset($_SESSION)) {
      session_start();
    }
  }
  
  public function init($account) {
    $_SESSION['account'] = $account;
  }
  
  public function reset() {
    session_destroy();
    session_start();
    session_regenerate_id();
  }

  public function logout() {
    setcookie('mytomatoes_remember', '', 1, '/');    
    $this->reset();
  }
  
  public function remember($account) {
    $code = $this->create_remember_me_code($account);
    setcookie('mytomatoes_remember', $code, time() + 14*24*60*60, '/'); // two weeks
    DatabaseConnection::instance()->query("INSERT INTO RememberCodes (code, account_id) VALUES (?, ?)", $code, $account->getId());
  }

  public function account() {
    return isset($_SESSION['account']) ? $_SESSION['account'] : null;
  }

  public function is_authenticated() {
    return isset($_SESSION['account']);
  }

  public function try_cookie_login() {
    if ($this->is_authenticated()) {
      return;
    }
    $code = $this->get_remember_code();
    if ($code && $row = $this->get_remembered_account($code)) {
      if ($this->code_is_still_valid($row)) {
        $this->cookie_login($this->get_account($row));
      }
      $this->delete_code($code);
    }
  }

  private function get_account($row) {
    $accounts = new AccountRepository();
    return $accounts->byId($row->account_id);
  }

  private function cookie_login($account) {
    $this->init($account);
    $this->remember($account);
    CookieLoginEvent::log($account);
  }

  private function code_is_still_valid($row) {
    return time() - strtotime($row->created_at) < 14*24*60*60;
  }

  private function delete_code($code) {
    DatabaseConnection::instance()->query("DELETE FROM RememberCodes WHERE code = ?", $code);
  }

  private function get_remembered_account($code) {
    return DatabaseConnection::instance()->query_row("SELECT account_id, created_at FROM RememberCodes WHERE code = ?", $code);
  }

  private function get_remember_code() {
    return isset($_COOKIE['mytomatoes_remember']) ? $_COOKIE['mytomatoes_remember'] : null;
  }

  private function create_remember_me_code($account) {
    $seed = time();
    do {
      $code = Account::hash_password($account->getUsername(), $seed--);
    } while (DatabaseConnection::instance()->query_row("SELECT account_id FROM RememberCodes WHERE code = ?", $code));
    return $code;
  }

  
}
?>