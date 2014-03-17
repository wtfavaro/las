<?php

class Stream {

  private $date;
  private $milsec;

  public function authentic(){

    // We're checking to see if the GET request is authentic.
    // If it is then we run it.
    if(isset($_GET['addr']) && isset($_GET['data']) && isset($_GET['date']) && isset($_GET['time'])){
      return true;
    } else {
      return false;
    }

  }

  private function udate($format, $utimestamp = null) {
    if (is_null($utimestamp))
      $utimestamp = microtime(true);

    $timestamp = floor($utimestamp);
    $milliseconds = round(($utimestamp - $timestamp) * 1000000);

    return gmdate(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
  }

  public function capture(){

    // Now we're going to capture the data sent by the remote.
    // If we start to overload the server, we'll have to work
    // on partitioning.
    global $db;

    // We set the date info based on the GET query.
    $this->setDateInfo();

    // Prepare the query.
    $query = sprintf("INSERT INTO packets (addr64, data, date_added, ms) VALUES (?, ?, ?, ?)");

    // Gather the data together.
    $data = array($_GET["addr"], $_GET["data"], $this->date, $this->milsec);

    // Execute the query.
    $db->prepare($query)->execute($data);

    echo "1";

  }

  private function setDateInfo(){

    // The date is already in an acceptable state, so we need
    // only parse the time into a string.
    $time_arr = explode("-", $_GET['time']);
    $time_str = $time_arr[0] . ":" . $time_arr[1] . ":" . $time_arr[2] . " " . $time_arr[4];
    
    // Now we set the date.
    $this->date = $_GET['date'] . " " . $time_str;
    $this->milsec = $time_arr[3]; 

  }

}

?>
