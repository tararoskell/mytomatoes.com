<?php
set_error_handler('airbagErrorHandler');

function airbagErrorHandler($code, $msg, $file, $line, $context) {
  $ajax = isset($_REQUEST['ajax']) ? $_REQUEST['ajax'] : null;
  if ($ajax == "json") {
    echo "{result: 'ERROR'}";
  } else if ($ajax === "load") {
    echo "<script>window.location = '/error.php';</script>";
  } else {
    header("Location: /error.php");
  }
  $body = "$msg at $file ($line), timed at " . date ("d-M-Y h:i:s", mktime()) . "\n\n" . print_r($context, TRUE);
  Airbag::log_error("mytomatoes CRASH!", $body);
  die();
}

class Airbag {
  
  public static function log_error($subject, $message) {
    require_once(dirname(__FILE__) . '/mailer.php');
    require_once(dirname(__FILE__) . '/log.php');
    try {
      @$mailer = new Mailer();
      @$mailer->send("magnars@gmail.com", $subject, $message);
    } catch (Exception $e) {
      //
    }
    try {
      $log = new Log("/tmp/airbag.txt");
      $log->message("[$subject]: $message\n");
    } catch (Exception $e) {
      //
    }
  }

}
?>