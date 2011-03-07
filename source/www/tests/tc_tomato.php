<?php
require_once('simpletest/unit_tester.php');
require_once('simpletest/reporter.php');
require_once('../model/tomato.php');

class TomatoTest extends UnitTestCase {
  
  function test_should_get_european_interval() {
    $tomato = Tomato::start(1, "1970-01-01 11:22:33");
    $tomato->complete("1970-01-01 13:57:00", "description");
    $this->assertEqual("11:22 - 13:57", $tomato->getEuropeanInterval());
  }

  function test_should_get_american_interval() {
    $tomato = Tomato::start(1, "1970-01-01 11:22:33");
    $tomato->complete("1970-01-01 13:57:00", "description");
    $this->assertEqual("11:22 AM - 01:57 PM", $tomato->getAmericanInterval());
  }

}

if (!isset($GLOBALS['testsuite'])) {
  $test = &new TomatoTest();
  $test->run(new HtmlReporter());
}
?>