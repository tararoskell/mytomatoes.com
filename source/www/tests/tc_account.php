<?php
require_once('simpletest/unit_tester.php');
require_once('simpletest/reporter.php');
require_once('../model/account.php');

class AccountTest extends UnitTestCase {
  
  function test_should_create_account() {
    $account = Account::create("magnars", "password");
    $this->assertEqual("magnars", $account->getUsername());
    $this->assertNotEqual("password", $account->getHashedPassword());
  }

}

if (!isset($GLOBALS['testsuite'])) {
  $test = &new AccountTest();
  $test->run(new HtmlReporter());
}
?>