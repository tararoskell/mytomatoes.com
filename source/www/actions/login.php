<?php
require_once(dirname(__FILE__) . '/action.php');
require_once(dirname(__FILE__) . '/../model/account.php');
require_once(dirname(__FILE__) . '/../model/session_manager.php');
require_once(dirname(__FILE__) . '/../infrastructure/log_events.php');

class LoginAction {
  
  public function __construct($action, $accounts) {
    $this->action = $action;
    $this->accounts = $accounts;
  }
  
  public function execute() {
    $this->action->assertPostedValues("username", "password");
    list($username, $password, $remember) = $this->action->getPostedValues("username", "password", "remember");
    $username = trim($username);
    if ($username == "" || $username === "username") {
      return $this->action->result("missing_username");
    }
    if ($password == "") {
      return $this->action->result("missing_password");
    }
    $account = $this->accounts->byUsername($username);
    if (! $account) {
      return $this->action->result("unknown_username");
    }
    if (! $account->matchPassword($password)) {
      return $this->action->result("wrong_password");
    }
    SessionManager::current()->init($account);
    if ($remember) {
      SessionManager::current()->remember($account);
    }
    LoginEvent::log($account, $remember);
    return $this->action->result("ok");
  }
  
}


if (!isset($GLOBALS['TEST'])) {
  $action = new Action();
  $accounts = new AccountRepository();
  $register = new LoginAction($action, $accounts);
  $register->execute();
}
?>
