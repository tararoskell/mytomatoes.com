<?php
require_once(dirname(__FILE__) . '/action.php');

class KeepSessionAliveAction {
  
  public function __construct($action) {
    $this->action = $action;
  }
  
  public function execute() {
    $this->action->requireAuthentication();
    return $this->action->result("ok");
  }
  
}


if (!isset($GLOBALS['TEST'])) {
  $action = new Action();
  $complete = new KeepSessionAliveAction($action);
  $complete->execute();
}
?>
