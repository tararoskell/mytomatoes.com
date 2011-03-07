<?php
class View {
  
  public function __construct() {
    
  }
  
  public static function is_showing($page) {
    return $page == preg_replace('/[^\/]*\//', '', $_SERVER['SCRIPT_FILENAME']);
  }
  
}
?>