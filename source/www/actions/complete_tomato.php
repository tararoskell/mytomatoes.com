<?php
require_once(dirname(__FILE__) . '/action.php');
require_once(dirname(__FILE__) . '/../model/session_manager.php');
require_once(dirname(__FILE__) . '/../model/tomato.php');

class CompleteTomatoAction {
  
  public function __construct($action, $tomatoes) {
    $this->action = $action;
    $this->tomatoes = $tomatoes;
  }
  
  public function execute() {
    $this->action->requireAuthentication();
    $this->action->assertPostedValues("start_time", "end_time", "description");
    list($start_time, $end_time, $description) = $this->action->getPostedValues("start_time", "end_time", "description");

    $account = SessionManager::current()->account();
    $tomato = Tomato::start($account->getId(), $start_time);
    $tomato->complete($end_time, $description);
    $this->tomatoes->save($tomato);

    return $this->action->result("ok");
  }
  
}


if (!isset($GLOBALS['TEST'])) {
  $action = new Action();
  $tomatoes = new TomatoRepository();
  $complete = new CompleteTomatoAction($action, $tomatoes);
  $complete->execute();
}
?>
