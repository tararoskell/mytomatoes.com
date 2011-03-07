<?php
require_once('simpletest/unit_tester.php');
require_once('simpletest/mock_objects.php');
require_once('simpletest/reporter.php');
require_once('../actions/action.php');
require_once('../model/account.php');
require_once('../model/session_manager.php');

Mock::generate('Action');
Mock::generate('AccountRepository');
Mock::generate('SessionManager');

class RegisterActionTest extends UnitTestCase {
  
  public function setup() {
    $GLOBALS['TEST'] = true;
    require_once('../actions/register.php');
    $this->action = new MockAction();
    $this->accounts = new MockAccountRepository();
    $this->sessions = new MockSessionManager();
    SessionManager::load($this->sessions);
    $this->register = new RegisterAction($this->action, $this->accounts, $this->sessions);
  }
  
  function setup_valid_account() {
    $this->action->setReturnValue('getPostedValues', array("magnars", "password", "password", true));
  }
  
  function test_should_not_allow_blank_username() {
    $this->action->setReturnValue('getPostedValues', array("", "password", "password", true));
    $this->action->expectOnce('result', array('missing_username'));
    $this->register->execute();
  }

  function test_should_not_allow_username_username() {
    $this->action->setReturnValue('getPostedValues', array("username", "password", "password", true));
    $this->action->expectOnce('result', array('missing_username'));
    $this->register->execute();
  }

  function test_should_not_allow_blank_password() {
    $this->action->setReturnValue('getPostedValues', array("magnars", "", "", true));
    $this->action->expectOnce('result', array('missing_password'));
    $this->register->execute();
  }

  function test_should_not_allow_duplicate_usernames() {
    $this->setup_valid_account();
    $this->accounts->setReturnValue('existsWithUsername', true);
    $this->action->expectOnce('result', array('unavailable_username'));
    $this->register->execute();
  }
  
  function test_should_ensure_passwords_are_equal() {
    $this->action->setReturnValue('getPostedValues', array("magnars", "pass", "word", true));
    $this->action->expectOnce('result', array('mismatched_passwords'));
    $this->register->execute();
  }
  
  function test_should_save_new_account() {
    $this->setup_valid_account();
    $this->accounts->expectOnce('save');
    $this->register->execute();
  }

  function test_should_initialize_session() {
    $this->setup_valid_account();
    $this->sessions->expectOnce('init');
    $this->register->execute();
  }
  
  function test_should_remember_if_flagged() {
    $this->setup_valid_account();
    $this->sessions->expectOnce('remember');
    $this->register->execute();
  }

  function test_should_not_remember_if_skipped() {
    $this->action->setReturnValue('getPostedValues', array("magnars", "password", "password", null));
    $this->sessions->expectNever('remember');
    $this->register->execute();
  }

  function test_should_return_ok_if_everything_works() {
    $this->setup_valid_account();
    $this->action->expectOnce('result', array('ok'));
    $this->register->execute();
  }
  
}

if (!isset($GLOBALS['testsuite'])) {
  $test = &new RegisterActionTest();
  $test->run(new HtmlReporter());
}

?>