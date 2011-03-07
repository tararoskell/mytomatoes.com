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
  
  <div id="welcome">
    <? if (isset($_GET['session'])) { ?>
      <p id="session_expired">sorry, your old session is lost to the ages</p>
    <? } else { ?>
      <p>mytomatoes.com helps you with the <a href="http://www.pomodorotechnique.com/">pomodoro technique</a> by <a href="http://cirillosscrapbook.wordpress.com/">Francesco Cirillo</a> - it's an online tomato kitchen timer and pomodoro tracker.</p>
    <? } ?>
    <form action="register.php">
      <a id="toggle_register_login" href="#">already registered?</a>
      <h3>register</h3>
      <div id="fields">
      <input type="text" id="username" name="username" />
      <input type="password" id="password" name="password" />
      <input type="password" id="password2" name="password2" />
      </div>
      <input id="submit" type="submit" value="loading.." disabled="disabled">
      <div id="remember_me"><input type="checkbox" id="remember" name="remember" checked="checked" /><label for="remember"> remember me</label></div>
    </form>
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

  <script src="javascript/input_hints.js" type="text/javascript"></script>
  <script src="javascript/register.js" type="text/javascript"></script>

  <!-- Dompressor: Eagerly cache validation, tutorial, button  -->
  
</div>

<? include("extras/google_analytics.inc"); ?>
</body>
</html>