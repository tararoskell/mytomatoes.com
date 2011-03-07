<?php
require_once('simpletest/unit_tester.php');
require_once('simpletest/mock_objects.php');
require_once('simpletest/reporter.php');
require_once('../actions/action.php');
require_once('../model/account.php');
require_once('../model/session_manager.php');

Mock::generate('Action');
Mock::generate('Account');
Mock::generate('AccountRepository');
Mock::generate('SessionManager');

class LoginActionTest extends UnitTestCase {
  
  public function setup() {
    $GLOBALS['TEST'] = true;
    require_once('../actions/login.php');
    $this->action = new MockAction();
    $this->accounts = new MockAccountRepository();
    $this->account = new MockAccount();
    $this->sessions = new MockSessionManager();
    SessionManager::load($this->sessions);
    $this->login = new LoginAction($this->action, $this->accounts);
  }
  
  function setup_valid_account() {
    $this->action->setReturnValue('getPostedValues', array("magnars", "password", true));
    $this->accounts->setReturnValue('byUsername', $this->account);
    $this->account->setReturnValue('matchPassword', true);
  }

  function test_should_not_allow_blank_username() {
    $this->action->setReturnValue('getPostedValues', array("", "password", true));
    $this->action->expectOnce('result', array('missing_username'));
    $this->login->execute();
  }

  function test_should_not_allow_username_username() {
    $this->action->setReturnValue('getPostedValues', array("username", "password", true));
    $this->action->expectOnce('result', array('missing_username'));
    $this->login->execute();
  }

  function test_should_not_allow_blank_password() {
    $this->action->setReturnValue('getPostedValues', array("magnars", "", true));
    $this->action->expectOnce('result', array('missing_password'));
    $this->login->execute();
  }

  function test_should_complain_about_unknown_username() {
    $this->action->setReturnValue('getPostedValues', array("magnars", "password", true));
    $this->accounts->setReturnValue('byUsername', null);
    $this->action->expectOnce('result', array('unknown_username'));
    $this->login->execute();
  }
  
  function test_should_complain_about_wrong_password() {
    $this->action->setReturnValue('getPostedValues', array("magnars", "password", true));
    $this->accounts->setReturnValue('byUsername', $this->account);
    $this->account->setReturnValue('matchPassword', false);
    $this->action->expectOnce('result', array('wrong_password'));
    $this->login->execute();
  }
  
  function test_should_initialize_session() {
    $this->setup_valid_account();
    $this->sessions->expectOnce('init');
    $this->login->execute();
  }
  
  function test_should_remember_if_flagged() {
    $this->setup_valid_account();
    $this->sessions->expectOnce('remember');
    $this->login->execute();
  }
  
  function test_should_not_remember_if_skipped() {
    $this->action->setReturnValue('getPostedValues', array("magnars", "password", null));
    $this->sessions->expectNever('remember');
    $this->login->execute();
  }
  
  function test_should_return_ok_if_everything_works() {
    $this->setup_valid_account();
    $this->action->expectOnce('result', array('ok'));
    $this->login->execute();
  }
  
}

if (!isset($GLOBALS['testsuite'])) {
  $test = &new LoginActionTest();
  $test->run(new HtmlReporter());
}

?>