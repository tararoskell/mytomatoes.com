<?php
class Log {
  private $filename;
  
  public function __construct($filename) {
    $this->filename = $filename;
  }
  
  public function message($message) {
    file_put_contents($this->filename, $message, FILE_APPEND);
  }
}
?>