<?php
require_once(dirname(__FILE__) . '/../../../config/mail_config.php');
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . '/../extras/zend_framework/library');
require_once('Zend/Mail.php');
require_once('Zend/Mail/Transport/Smtp.php');

class Mailer {
  private $transport;
  
  public function __construct($host = false, $port = false) {
    if (!$host) { $host = MailConfig::HOST; }
    if (!$port) { $port = MailConfig::PORT; }
    $this->transport = new Zend_Mail_Transport_Smtp($host, array('port' => $port));
  }
  
  public function send($address, $subject, $message, $from = false) {
    $mail = new Zend_Mail('utf-8');
    if ($from) {
      $mail->setFrom($from["emailaddress"], $from["name"]);
    } else {
      $mail->setFrom('hjelp@adventur.no', 'Adventur Delux');
    }
    if (MailConfig::OVERRIDE_ADDRESS) {
      $mail->addTo(MailConfig::OVERRIDE_ADDRESS);
    } else {
      $mail->addTo($address);
    }
    $mail->setSubject(MailConfig::ADD_TO_SUBJECT . $subject);
    $mail->setBodyText($message);
    $mail->send($this->transport);
  }
  
}
?>