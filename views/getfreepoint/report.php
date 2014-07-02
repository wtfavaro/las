<h1>Not prepared to generate a report.</h1>

<?php

if (!isset($_POST["path"])){
  echo "No file found.";
} else {
  $LogFile = new LogFile($_POST["path"]);
  print_r($LogFile->contents);
}

class LogFile {

public $contents = array();

public function __construct($path){

  $error = false;

  $handle = @fopen($path, "r");

  if ($handle) {
      while (($buffer = fgets($handle, 4096)) !== false) {
          $this->contents[] = explode(",", $buffer);
      }
      if (!feof($handle)) {
          $error = true;
      }
      fclose($handle);
  }

  if (!$error){

    unset($this->contents[0]);

    // Convert the date string at column[0] into a datetime object.
    $this->contents = $this->_AddDateTimeToContent($this->contents);

  }
}

private function _AddDateTimeToContent($content){

  for($i=0; $i<count($content); $i++) {

    // Get the current row
    if (!isset($content[$i])) continue;
    $row = $content[$i];

    // Get the parts of the timestamp
    $timestamp = explode(" ", $row[0]);

    // Check if this is a valid timestamp
    if (!isset($timestamp[2])) continue;

    // Convert Timestamp into structured array
    $time = array(
      "date"   => "",
      "time"   => "",
      "ms"     => "",
      "suffix" => ""
    );

      $time["date"] = explode("/", $timestamp[0]);
      $time["time"] = explode(":", $timestamp[1]);
      $time["suffix"] = $timestamp[2];
      $ms = explode(".", $time["time"][2]);
      $time["time"][2] = $ms[0];
      $time["ms"] = $ms[1];

    // Calculation for 24 hour clock
    if($time["suffix"] == "PM" && $time["time"][0] != 12){
      $time["time"][0] = $time["time"][0] + 12;
    } elseif($time["suffix"] == "PM" && $time["time"][0] != 12) {
      $time["time"][0] = 0;
    }
    
    // Now make the datetime
    $datetime = new DateTime(
                  $time["date"][0] . "/" . $time["date"][1] . "/" . $time["date"][2] . " " .
                  $time["time"][0] . ":" . $time["time"][1] . ":" . $time["time"][2]
              );
    
    // Add timestamp to the row
    $row[0] = $datetime;

    // Re-add the row to the content
    $content[$i] = $row;
  }

  return $content;

}

}

?>
