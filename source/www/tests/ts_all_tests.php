<?php
require_once('simpletest/unit_tester.php');
require_once('simpletest/reporter.php');

$GLOBALS['TEST'] = true;
$GLOBALS['testsuite'] = true;

require_once('../infrastructure/database_connection.php');
file_put_contents('/tmp/test-pom-sql-failures.txt', '');
DatabaseConnection::$logfile = '/tmp/test-pom-sql-failures.txt';

$test = &new TestSuite('All tests');
$test->addTestFile('tc_account.php');
$test->addTestFile('tc_account_repository.php');
$test->addTestFile('tc_no_sql_errors.php');
$test->addTestFile('tc_register_action.php');
$test->addTestFile('tc_login_action.php');

if (sizeof($argv) > 1 && $argv[1] == "text") {
  $test->run(new TextReporter());
} else {
  $test->run(new HtmlReporter());
}

DatabaseConnection::reset();
DatabaseConnection::instance()->query("DELETE FROM EventLog WHERE ip_address = 't.e.s.t'");
?>