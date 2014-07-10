<?php

if (!isset($_POST["path"])){
  echo "No file found.";
} else {
  // LogFile generates an array-based Log File located in object->contents.
  $LogFile = new LogFile($_POST["path"]);
  $LogICounter = new LogFileInputCounter($LogFile->contents, "BENDER");
  echo "Result: " . $LogICounter->getCount();
}

class LogFile {

public $inputs = array();
public $contents = array();

public function __construct($path){

  $error = false;

  $handle = @fopen($path, "r");

  // Access the file.
  if ($handle) {
      while (($buffer = fgets($handle, 4096)) !== false) {
          $this->contents[] = explode(",", $buffer);
      }
      if (!feof($handle)) {
          $error = true;
      }
      fclose($handle);
  }

  // Check if an error was generated while accessing the file.
  if (!$error){
    unset($this->contents[0]);

    // Convert the date string at column[0] into a datetime object.
    $this->contents = $this->_AddDateTimeToContent($this->contents);
  }
}

private function _AddDateTimeToContent($content){

  for($i=0; $i<count($content)+1; $i++) {

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
}

class LogFileInputCounter{

// Row information.
private $row;

// Instance related information
private $input_focus;
private $logfile_array;

// Capture increment values
private $current_increment;

// Counting from last zero
private $active_count_current_value;
private $active_count_last_value;
private $active_count_start_value;
private $active_count_total;

// Accumulative count
private $accumulative_count_total;

// Note flags along the way
private $encountered_DI_reset = false;
private $encountered_remote_power_up = false;

public function __construct($logfile, $input_name){
  $this->input_focus = $input_name;
  $this->logfile_array = $logfile;
  $this->increment();
}
private function increment(){
  $logfile_length = count($this->logfile_array);
  for($i = 0; $i < $logfile_length+1; $i++){
    // Continue if no row
    if (!isset($this->logfile_array[$i])) continue;
    // Save a pointer to the row in this instance
    $this->row = $this->logfile_array[$i];

    if ($this->row[3] == $this->input_focus){
      // Save the previous and current increment value
      $this->current_increment = $i;
      // Keep active count
      $this->keepActiveCount();
      // Add active total
      $this->addToActiveTotal();
      // Test for notes
      $this->testForNotes();
    }
  }
}
private function addToActiveTotal(){
  // Check if the counter is incrementing upward -- add to the total if true
  if ($this->row[5] != "" || $this->row[5] > $this->active_count_start_value){
    $this->active_count_total = $this->row[5] - $this->active_count_start_value;
  }
  // If it isn't incrementing -- damn -- we've got to deal with a rollover.
  else {
    if($this->encounteredResetOrPowerUpNote()){
      // Because we got a reset or power note, we know the count
      // was cut short. We need to add active count to total and start fresh.
      $this->addActiveCountToTotal();
      $this->startNewActiveCount();
    } else {
      // Because there was no note, we know a rollover happened
      $this->accmulative_count_total = (($this->active_count_start_value - 65535)*-1);
      $this->startNewActiveCount();
    }
  }
}
private function testForNotes(){
  if ($this->row[7] == "DI Reset") $this->encountered_DI_reset = true;
  if ($this->row[7] == "Remote Power Up") $this->encountered_remote_power_up = true;
}
private function encounteredResetOrPowerUpNote(){
  return ($this->encountered_DI_reset || $this->encountered_remote_power_up ? true : false);
}
private function keepActiveCount(){
  $this->active_count_last_value = $this->active_count_current_value;
  $this->active_count_current_value = $this->row[5];
}
private function addActiveCountToTotal(){
  $accumulative_total = $this->accumulative_count_total;
  $this->accumulative_count_total = $accumulative_total + $this->active_count_total;
}
private function startNewActiveCount(){
  // Save state
  $this->active_count_start_value = $this->active_count_current_value;
  // Reset state
  $this->active_count_last_value = 0;
  $this->active_count_total = 0;
  $this->encountered_DI_reset = false;
  $this->encountered_remote_power_up = false;
}
public function getCount(){
  return $this->accumulative_count_total;
}
}
?>

<script type="text/javascript">
var content = "<?php echo addslashes(json_encode($LogFile->contents)); ?>";
var logfile = jQuery.parseJSON( content );
var Analytics = new Analytics();
Analytics.FirstActivity();

function Analytics(){

  this.FirstActivity = function(){
    for (var row in logfile) {
      //
      console.log(logfile[row]);
    }
  }

}

</script>
