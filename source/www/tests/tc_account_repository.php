<?php
require_once('simpletest/unit_tester.php');
require_once('simpletest/mock_objects.php');
require_once('simpletest/reporter.php');
require_once('../model/account.php');

Mock::generate('DatabaseConnection');
Mock::generate('Account');


class AccountRepositoryTest extends UnitTestCase {
  
  function setup() {
    $this->setup_account();
    $this->db = new MockDatabaseConnection();
    DatabaseConnection::load($this->db);
    $this->repo = new AccountRepository();
  }
  
  function setup_account() {
    $this->account = new MockAccount();
    $this->account->setReturnValue("getUsername", "magnars");
    $this->account->setReturnValue("getHashedPassword", "hash");
    $this->account->setReturnValue("getRandomSalt", "salt");
  }
  
  function test_should_save_account_and_set_id() {
    $this->db->expectOnce("insert");
    $this->db->setReturnValue("insert", 17);
    $this->account->expectOnce("setId", array(17));
    $this->repo->save($this->account);
  }

  function test_should_know_if_a_username_exists() {
    $this->db->setReturnValue("query_value", 1);
    $this->assertEqual(true, $this->repo->existsWithUsername("magnars"));
  }
  
}

if (!isset($GLOBALS['testsuite'])) {
  $test = &new AccountRepositoryTest();
  $test->run(new HtmlReporter());
}
?>