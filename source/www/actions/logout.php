<?php
require_once(dirname(__FILE__) . '/action.php');
require_once(dirname(__FILE__) . '/../model/account.php');
require_once(dirname(__FILE__) . '/../model/session_manager.php');
require_once(dirname(__FILE__) . '/../infrastructure/log_events.php');

class LogoutAction {
  
  public function __construct($action) {
    $this->action = $action;
  }
  
  public function execute() {
    SessionManager::current()->logout();
    return $this->action->result("ok");
  }
  
}


if (!isset($GLOBALS['TEST'])) {
  $action = new Action();
  $register = new LogoutAction($action);
  $register->execute();
}
?>
