<?php
require_once(dirname(__FILE__) . '/action.php');
require_once(dirname(__FILE__) . '/../model/account.php');
require_once(dirname(__FILE__) . '/../model/session_manager.php');
require_once(dirname(__FILE__) . '/../infrastructure/log_events.php');

class RegisterAction {
  
  public function __construct($action, $accounts) {
    $this->action = $action;
    $this->accounts = $accounts;
  }
  
  public function execute() {
    $this->action->assertPostedValues("username", "password", "password2");
    list($username, $password, $password2, $remember) = $this->action->getPostedValues("username", "password", "password2", "remember");
    $username = trim($username);
    if ($username == "" || $username === "username") {
      return $this->action->result("missing_username");
    }
    if ($password == "") {
      return $this->action->result("missing_password");
    }
    if ($this->accounts->existsWithUsername($username)) {
      return $this->action->result("unavailable_username");
    }
    if ($password != $password2) {
      return $this->action->result("mismatched_passwords");
    }
    $account = Account::create($username, $password);
    $this->accounts->save($account);
    SessionManager::current()->init($account);
    if ($remember) {
      SessionManager::current()->remember($account);
    }
    RegisterEvent::log($account, $remember);
    return $this->action->result("ok");
  }
  
}


if (!isset($GLOBALS['TEST'])) {
  $action = new Action();
  $accounts = new AccountRepository();
  $register = new RegisterAction($action, $accounts);
  $register->execute();
}
?>
