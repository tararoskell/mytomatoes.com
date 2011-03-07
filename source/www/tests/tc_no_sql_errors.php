<?php
require_once('simpletest/unit_tester.php');
require_once('simpletest/reporter.php');

class NoSqlErrorsTest extends UnitTestCase {
  
  function test_should_have_created_no_sql_errors() {
    $this->assertEqual('', file_get_contents('/tmp/test-pom-sql-failures.txt'));
  }

}

if (!isset($GLOBALS['testsuite'])) {
  $test = &new ClassTest();
  $test->run(new HtmlReporter());
}
?>