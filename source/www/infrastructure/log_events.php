<?php
require_once('database_connection.php');

class LogEvent {

  public function getIpAddress() {
    if (isset($GLOBALS['TEST'])) {
      return 't.e.s.t';
    } else if (isset($_SERVER['REMOTE_ADDR'])) {
      return $_SERVER['REMOTE_ADDR'];
    } else {
      return '0.0.0.0';
    }
  }
    
  public function __construct($event) {
    $this->event = $event;
    $this->ip_address = $this->getIpAddress();
  }

  public function log() {
    DatabaseConnection::instance()->query("INSERT INTO EventLog (account_id, event, ip_address, details) VALUES (?, ?, ?, ?)", $this->account->getId(), $this->event, $this->ip_address, $this->details);
  }
  
  public function set_account($account) {
    $this->account = $account;
  }
  
  public function set_details($details) {
    $this->details = $details;
  }
  
}

class RegisterEvent {
  
  public static function log($account, $remember) {
    $event = new LogEvent('registered');
    $event->set_account($account);
    $event->set_details($remember ? "remembered" : "not_remembered");
    $event->log();
  }
  
}

class LoginEvent {
  
  public static function log($account, $remember) {
    $event = new LogEvent('logged_in');
    $event->set_account($account);
    $remember = $remember ? "remembered" : "not_remembered";
    $event->set_details("manual ($remember)");
    $event->log();
  }
  
}

class CookieLoginEvent {
  
  public static function log($account) {
    $event = new LogEvent('logged_in');
    $event->set_account($account);
    $event->set_details("cookie");
    $event->log();
  }
  
}

// class ChangeEmailEvent {
//   
//   public static function log($account, $old_email_address, $new_email_address) {
//     $event = new LogEvent('changed_email');
//     $event->set_account($account);
//     $event->set_details("Changed $old_email_address to $new_email_address.");
//     $event->log();
//   }
//   
// }
// 
// class ChangePasswordEvent {
//   
//   public static function log($account, $context) {
//     $event = new LogEvent('changed_password');
//     $event->set_account($account);
//     $event->set_details("Context was $context.");
//     $event->log();
//   }
//     
// }
// 
// class CreatePlayerEvent {
//   
//   public static function log($account, $player) {
//     $event = new LogEvent('created_player');
//     $event->set_account($account);
//     $event->set_details($player->getName()." (".$player->getId().")");
//     $event->log();
//   }
//   
// }
// 
// 
// class MergeAccountsEvent {
//   
//   public static function log($deleted_account, $kept_account) {
//     $event = new LogEvent('merged_accounts');
//     $event->set_account($deleted_account);
//     $event->set_details("merged into ".$kept_account->getNickName()." (".$kept_account->getId().")");
//     $event->log();
//   }
//   
// }
// 
// class VerifyEmailEvent {
//   
//   public static function log($account) {
//     $event = new LogEvent('verified_email');
//     $event->set_account($account);
//     $event->set_details("Verified ".$account->getEmailAddress());
//     $event->log();
//   }
//   
// }

?>