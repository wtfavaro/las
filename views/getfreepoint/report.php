<?php

if (!isset($_POST["path"])){
  echo "No file found.";
} else {

  // LogFile generates an array-based Log File located in object->contents.
  $LogFile = new LogFile($_POST["path"]);
}

class LogFile {

public $inputs = array();
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
    $row[0] = $datetime->format("Y/m/d H:i:s");

    // Re-add the row to the content
    $content[$i] = $row;

    // As an aside, add the input name to the inputs property
    if (isset($row[3]) && $row[3] != "" && !in_array($row[3], $this->inputs)) array_push($this->inputs, $row[3]);
  }

  return $content;

}

public function FirstCycle(){

  /*
      The first cycle does not merely seek out the first instance of activity (when Cell Monitor turns on).
      Instead, it looks for the first instance where the state is 1.
  */

  foreach($this->contents as $row){
    if($row[0] != "" && $row[4] != "" && $row[4] > 0){
      echo "First Cycle: " . $row[0]->format("Y/m/d H:i:s");
      return true;
    }
  }
  return false;
}

}

?>

<script type="text/javascript">
  var content = "<?php echo addslashes(json_encode($LogFile->contents)); ?>";
  var logfile = jQuery.parseJSON( content );


function Analytics(){

  this.FirstActivity = function(){
    for (var row in logfile) {
      //
    }
  }

}

</script>
