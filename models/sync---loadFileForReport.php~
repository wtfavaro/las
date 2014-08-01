<?php

namespace Log;
class Loader {
  public static function GetAllRows($args){
    if (!isset($args["path"])) return false;
    $LogFile = new LogFile_LoadFileForReport($args["path"]);
    echo json_encode($LogFile->contents);
  }
}
?>


<?php

class LogFile_LoadFileForReport {

public $inputs = array();
public $contents = array();

public function __construct($path){

  // Open the file
  $lines  = @file($path);

  // Save it as a global property
  $this->contents = $lines;
  unset($lines);

  // Convert the date string into a datetime object.
  // $this->contents = $this->_AddDateTimeToContent($this->contents);
}

private function _AddDateTimeToContent($content){

  for($i=0; $i<count($content)+1; $i++) {

    // Get the current row
    if (!isset($content[$i])) continue;
     
    // Explode
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
    $row[0] = $datetime->format("Y/m/d H:i:s");

    // Re-add the row to the content
    $content[$i] = $row;

    // As an aside, add the input name to the inputs property
    if (isset($row[3]) && $row[3] != "" && !in_array($row[3], $this->inputs)) array_push($this->inputs, $row[3]);
  }

  return $content;

}
}

?>
