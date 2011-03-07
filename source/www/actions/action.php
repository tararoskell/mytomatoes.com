<?php
if (!isset($GLOBALS['TEST'])) {
  require_once(dirname(__FILE__) . '/../infrastructure/airbag.php');
}
require_once(dirname(__FILE__) . '/../infrastructure/json.php');
require_once(dirname(__FILE__) . '/../model/session_manager.php');

class Action {
  
  public function __construct() {
    $this->sessions = SessionManager::current();
  }

  public function result($result) {
    echo JSON::result($result);
    exit;
  }

  public function requireAuthentication() {
    $this->sessions->try_cookie_login();
    if (! $this->sessions->is_authenticated()) {
      $this->result("not_logged_in");
    }
  }

  public function assertPostedValues() {
    $required_vars = func_get_args();
    foreach ($required_vars as $var) {
      if (!isset($_POST[$var])) {
        $this->result("missing_posted_values");
      }
    }
  }

  public function getPostedValues() {
    $vars = func_get_args();
    $ret = array();
    foreach ($vars as $var) {
      $ret[] = isset($_POST[$var]) ? $_POST[$var] : null;
    }
    return $ret;
  }
  
}
?>