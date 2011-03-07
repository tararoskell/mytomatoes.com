<?php
require_once(dirname(__FILE__) . '/view.php');
require_once(dirname(__FILE__) . '/../model/session_manager.php');
require_once(dirname(__FILE__) . '/../model/tomato.php');

class CompletedTomatoesView {
  
  public function __construct($account, $tomato_repo) {
    $this->clocktype = $account->getPreference("use_american_clock") == "true" ? "american_clock" : "european_clock";
    $this->account_id = $account->getId();
    $this->tomato_repo = $tomato_repo;
    $this->tomatoes = null;
    $this->days = array();
  }

  public function contents() {
    $this->fetch_tomatoes();
    $this->split_tomatoes_into_days();
    return $this->render_days_html();
  }
  
  private function fetch_tomatoes() {
    $this->tomatoes = $this->tomato_repo->byAccountId($this->account_id);
  }

  private function split_tomatoes_into_days() {
    foreach ($this->tomatoes as $tomato) {
      $this->day_for($tomato)->add($tomato);
    }
  }

  private function day_for($tomato) {
    $date = $tomato->getStartDate();
    if (! isset($this->days[$date])) {
      $this->days[$date] = new Day($date);
    }
    return $this->days[$date];
  }

  private function render_days_html() {
    $html = "<div class='$this->clocktype'>";
    foreach ($this->days as $day) {
      $html .= $this->render_day_html($day);
    }
    return $html."</div>";
  }
  
  private function render_day_html($day) {
    $html = "<h3><strong>".$day->date()."</strong> <span>".$day->number_of_tomatoes()."</span></h3>\n";
    $html .= "<ul>\n";
    foreach ($day->tomatoes() as $index => $tomato) {
      if ($tomato->getDescription()) {
        $description = $tomato->getDescription();
      } else {
        $description = "tomato #" . (sizeof($day->tomatoes()) - $index) . " finished";
      }
      $html .= "  <li>";
      $html .= "<span class='eurotime'>".$tomato->getEuropeanInterval()."</span>";
      $html .= "<span class='ameritime'>".$tomato->getAmericanInterval()."</span>";
      $html .= " $description </li>\n";
    }
    return "$html</ul>\n";
  }
  
}

class Day {
  
  public function __construct($date) {
    $this->date = $date;
    $this->tomatoes = array();
  }
  
  public function add($tomato) {
    $this->tomatoes[] = $tomato;
  }

  public function date() {
    return $this->date;
  }
  
  public function number_of_tomatoes() {
    $num = sizeof($this->tomatoes);
    $plural = $num == 1 ? "" : "es";
    return "$num finished tomato$plural";
  }
  
  public function tomatoes() {
    return $this->tomatoes;
  }
  
}

if (View::is_showing("completed_tomatoes.php")) {
  $sessions = SessionManager::current();
  $sessions->try_cookie_login();
  if ($sessions->is_authenticated()) {
    $view = new CompletedTomatoesView($sessions->account(), new TomatoRepository());
    echo $view->contents();
  } else {
    echo "<script>window.location = '/';</script>";
  }
}

?>