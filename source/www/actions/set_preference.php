<?php
require_once(dirname(__FILE__) . '/action.php');
require_once(dirname(__FILE__) . '/../model/session_manager.php');

class SetPreferenceAction {
  
  public function __construct($action) {
    $this->action = $action;
  }
  
  public function execute() {
    $this->action->requireAuthentication();
    $this->action->assertPostedValues("name");
    list($name, $value) = $this->action->getPostedValues("name", "value");
    SessionManager::current()->account()->setPreference($name, $value ? $value : "y");
    return $this->action->result("ok");
  }
  
}


if (!isset($GLOBALS['TEST'])) {
  $action = new Action();
  $complete = new SetPreferenceAction($action);
  $complete->execute();
}
?>
