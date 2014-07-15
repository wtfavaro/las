<?php
if (!isset($_POST["path"])){
  echo "No file found.";
} else {
  // LogFile generates an array-based Log File located in object->contents.
  $LogFile = new LogFile($_POST["path"]);
  //$LogICounter = new LogFileInputCounter($LogFile->contents, "INPUT 4");
  //echo "Result: " . $LogICounter->getCount();
}

class LogFile {

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
    // Explode the row, saving a pointer
    $this->row = explode(",", $this->logfile_array[$i]);
    // We test for notes.
    $this->testForNotes();
    // If there is no counter value, we move on.
    if($this->row[5]=="") continue;

    if ($this->row[3] == $this->input_focus){
      // Assign start value
      $this->assignStartValue();
      // Save the previous and current increment value
      $this->current_increment = $i;
      // Keep active count
      $this->keepActiveCount();
      // Add active total
      $this->addToActiveTotal();
    }
  }

  $this->addActiveCountToTotal();
}
private function assignStartValue(){
  if($this->active_count_start_value == "") $this->active_count_start_value = $this->row[5];
}
private function addToActiveTotal(){

  // Check if the counter is incrementing upward -- add to the total if true
  if ($this->row[5] > $this->active_count_start_value){
    $this->active_count_total = $this->row[5] - $this->active_count_start_value;
  }
  // If it isn't incrementing -- damn -- we've got to deal with a rollover.
  else {
    if($this->encounteredResetOrPowerUpNote()){
      echo "Rollover with note.";
      // Because we got a reset or power note, we know the count
      // was cut short. We need to add active count to total and start fresh.
      $this->addActiveCountToTotal();
      $this->startNewActiveCount();
    } else {
      echo "Rollover without note.";
      // Because there was no note, we know a rollover happened
      $this->accumulative_count_total = $this->accumulative_count_total + (($this->active_count_start_value - 65535)*-1);
      $this->startNewActiveCount();
    }
  }
}
private function testForNotes(){
  if (trim($this->row[7], " ") == "DI Reset") $this->encountered_DI_reset = true;
  if (trim($this->row[7], " ") == "Remote Power Up") $this->encountered_remote_power_up = true;
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
console.log("Reaches script.");

var content = "<?php if (isset($LogFile->contents[1])) echo addslashes(json_encode($LogFile->contents)); ?>";
var logfile = jQuery.parseJSON( content );

var data = {
  inputs: new Array()
}

var h=0;

$('body').html("<h1>Generating Report</h1>");

// Process Analytics //
var Analytics = {

      Inputs: Array(),

      __init: function(callback){ 
        this.getInputList();

        $.each(this.Inputs, function(key, value){
          Analytics.Run(value);
        });

        callback();
      },

      getInputList: function(){
        for (var i in logfile){
          logfile[i] = logfile[i].split(",");
          getInputNames(logfile[i]);
        }

        function getInputNames(row){
          if (Analytics.Inputs.indexOf(row[3]) === -1 && row[3] !== "Name" && row[3] !== ""){
            Analytics.Inputs.push(row[3]);
          }
        }
      },

      Run: function(inputName){

        // Current input focus
        var input_focus = inputName;

        // Active count
        var active_count = 0;

        // Accumulative count
        var accumulative_count = 0;

        // Counter
        var start_value;                                          // defaults to zero after the first go
        var this_count_value;
        var last_count_value;
        var rIndex = 0;

        // Notes
        var encountered_DI_reset = false;
        var encountered_power_up = false;

        // Start the function
        console.log("Starting to iterate...");        
        iterator();

        // Iterator Function
        function iterator(){
              for (var i in logfile){

                // Isolate row
                var row = logfile[i];

                // Looking for notes
                lookForRolloverNote(row);

                // Go no further if there is no integer at counter value
                // And go no further if this isn't the input name we're looking for
                if (row[3] != input_focus || !isInt(row[5])) continue;

                // Keeping count
                last_count_value = this_count_value;
                this_count_value = row[5];

                // This is okay to be added to the active count
                if (this_count_value > last_count_value){
                  addToActiveCount();
                }
                // Now we have to deal with a damn rollover
                else if(this_count_value < last_count_value && didEncounterNote()) {
                  console.log(row);
                  addToAccumulativeCount();
                  clearVariablesForNextActiveCount();
                }
              }
        }

        function isInt(n){
          if(n/n===1) return true;
          else return false;
        }

        function lookForRolloverNote(row){

          if (row[7].trim() === "DI Reset") encountered_DI_reset = true;
          else if (row[7].trim() === "Remote Power Up") encountered_power_up = true;

        }

        function didEncounterNote(){
          if (encountered_DI_reset || encountered_power_up) return true;
          else return false;
        }

        function addToActiveCount(){
          active_count = this_count_value - start_value;
        }

        function addToAccumulativeCount(){
          //console.log(parseInt(accumulative_count) + ((start_value - 65535)*-1) + active_count);
          var count = accumulative_count;
          accumulative_count = ((start_value - 65535)*-1) + active_count;
        }

        function clearVariablesForNextActiveCount(){
          start_value           = this_count_value;
          active_count          = 0;
          current_value         = 0;
          last_value            = 0;
          encountered_DI_reset  = false;
          encountered_power_up  = false;
        }
     }
}

Analytics.__init(function(){
  console.log("Finished iterating.");
});
</script>
