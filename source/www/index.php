<?php
require_once(dirname(__FILE__) . '/infrastructure/airbag.php');
require_once(dirname(__FILE__) . '/model/session_manager.php');
require_once(dirname(__FILE__) . '/views/completed_tomatoes.php');

class IndexHelper {
  public $completed_tomatoes;

  public function __construct() {
    $this->sessions = SessionManager::current();
    $this->sessions->try_cookie_login();
    if (! $this->sessions->is_authenticated()) {
      include("login.php");
      exit;
    }
    $ct_view = new CompletedTomatoesView($this->sessions->account(), new TomatoRepository());
    $this->completed_tomatoes = $ct_view->contents();
  }

  function show_tutorial() {
    return ! $this->sessions->account()->getPreference("hide_tutorial");
  }

  function ticking_checked() {
    return $this->sessions->account()->getPreference("play_ticking") == "true" ? "checked='checked'" : "";
  }

  function american_clock_checked() {
    return $this->sessions->account()->getPreference("use_american_clock") == "true" ? "checked='checked'" : "";
  }

}
$helper = new IndexHelper();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
  "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8">
  <title>mytomatoes.com</title>
  <link rel="stylesheet" href="theme/css/reset.css" type="text/css" media="screen">
  <link rel="stylesheet" href="theme/css/master.css" type="text/css" media="screen">
</head>

<body>
<div id="main">

  <div id="header">
    <h1>mytomatoes.com<div> simple pomodoro tracking</div></h1>
  </div>

  <noscript>
    <style type="text/css" media="screen"> #states, #done, #welcome {display: none;} </style>
    <div id="noscript"><p>mytomatoes.com is a tool for use with the <a href="http://www.pomodorotechnique.com/">pomodoro technique</a> by <a href="http://cirillosscrapbook.wordpress.com/">Francesco Cirillo</a>. <em>It doesn't work without Javascript.</em> Sorry.</p></div>
  </noscript>

  <ul id="states">
    <li id="waiting">
      <div id="flash_message">&nbsp;</div>
      <a href="#">start tomato</a>
    </li>
    <li id="working">
      <div id="time_left">25 min </div>
      <div id="cancel"><a href="#">squash tomato</a></div>
    </li>
    <li id="stop_working">
      <div id="no_time_left">0:00</div>
      <div id="break">time for a break</div>
    </li>
    <li id="enter_description">
      <div id="congratulations">congrats! <span>first</span> finished tomato today</div>
      <div id="description">what did you do?</div>
      <form><input type="text" maxlength="255" /></form>
      <div id="void">or <a href="#">squash tomato</a></div>
    </li>
    <li id="on_a_break">
      <div id="break_left">5 min</div>
      <div id="well_deserved">a well deserved break</div>
      <div id="longer_break" class="longer_break_closed"><a id="toggle_longer_break" href="#">take a longer break</a><span>: <a href="#">10</a> <a href="#">15</a> <a href="#">20</a> <a href="#">25</a> min</span></div>
    </li>
    <li id="break_over">
      <div id="no_break_left">0:00</div>
      <div id="back_to_work">back to work!</div>
    </li>
  </ul>

  <div id="preferences_container">
    <div id="preferences">
      <h3>preferences</h3>
      <ul>
        <li id="ticking_preference">
          <label><input type="checkbox" name="play_ticking" <?= $helper->ticking_checked(); ?> /> Play ticking sound when working on a tomato</label>
          <div class="note">Not a fan of the ticking? I recommend <a href="http://simplynoise.com" target="_blank">simplynoise.com</a>!</div>
        </li>
        <li id="clock_preference">
          <label><input type="checkbox" name="use_american_clock" <?= $helper->american_clock_checked(); ?> /> Use 12-hour clock</label>
        </li>
      </ul>
    </div>
  </div>

  <? if ($helper->show_tutorial()) { include("views/tutorial.php"); } ?>

  <div id="done">
    <?= $helper->completed_tomatoes ; ?>
  </div>

  <div id="audio">
    <audio id="alarm_audio" autobuffer preload>
      <source src="sounds/alarm.ogg" />
      <source src="sounds/alarm.mp3" />
      <source src="sounds/alarm.wav" />
    </audio>
    <audio id="ticking_audio_1" autobuffer preload>
      <source src="sounds/ticking.ogg" />
      <source src="sounds/ticking.mp3" />
      <source src="sounds/ticking.wav" />
    </audio>
    <audio id="ticking_audio_2" autobuffer preload>
      <source src="sounds/ticking.ogg" />
      <source src="sounds/ticking.mp3" />
      <source src="sounds/ticking.wav" />
    </audio>
  </div>

  <div id="push"></div>
</div>
<div id="footer">
  <? include("views/footer.php"); ?>

  <script src="javascript/external/jquery.js" type="text/javascript"></script>
  <script src="javascript/external/jquery.color.js" type="text/javascript"></script>
  <script src="javascript/external/jquery.url.js" type="text/javascript"></script>
  <script src="javascript/external/shortcut.js" type="text/javascript"></script>
  <script src="javascript/external/date.js" type="text/javascript"></script>
  <script src="javascript/external/AC_RunActiveContent.js" type="text/javascript"></script>
  <script src="javascript/library.js" type="text/javascript"></script>
  <script src="javascript/ajax_service.js" type="text/javascript"></script>

  <script src="javascript/countdown.js" type="text/javascript"></script>
  <script src="javascript/animation.js" type="text/javascript"></script>
  <script src="javascript/sound_player.js" type="text/javascript"></script>
  <script src="javascript/preferences.js" type="text/javascript"></script>
  <script src="javascript/index.js" type="text/javascript"></script>
  <script type="text/javascript" charset="utf-8">
    MT.initialize_preferences();
    MT.initialize_index();
  </script>
</div>

<? include("extras/google_analytics.inc"); ?>
</body>

</html>