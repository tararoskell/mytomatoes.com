<?php
header('Content-type: text/csv');
header('Content-Disposition: attachment; filename="mytomatoes.csv"');

require_once(dirname(__FILE__) . '/views/completed_tomatoes.php');

$sessions = SessionManager::current();
$sessions->try_cookie_login();
if ($sessions->is_authenticated()) {
  $view = new CompletedTomatoesView($sessions->account(), new TomatoRepository());
  echo $view->csv();
} else {
  echo "<script>window.location = '/';</script>";
}

?>