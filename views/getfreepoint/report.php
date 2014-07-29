

<script type="text/javascript">

// Get all rows
data = { path: "<?php echo $_POST['path']; ?>" };
httprequest('\\Log\\Loader', 'GetAllRows', data, function(resp){
  if(resp === 0 || resp === "0" || resp === "" ){
    alert("Cannot load this report. Try again.");
  } else {
    var logfile = jQuery.parseJSON(resp);
    var Graph = {
      SDK: new Analytics(logfile)
    }
  }
});


/*
var data = {
  inputs: new Array()
}

var h=0;

$('body').html("<h1>Generating Report</h1>");

// Engine catalyst
function Main(resp){

    var logfile = jQuery.parseJSON( resp );
    // Initialize the Analytics class //
    var InputClass = new Inputs(logfile);
    InputClass.Get();
}

// Input List //
function Inputs(logfile){

  this.List = Array();
  this.Instance = Array();
  var inputCount = 0;
  var instanceFinishedCount = 0;
  var self = this;

  this.Get = function(){
    for (var i in logfile){
      logfile[i] = logfile[i].split(",");
      getInputNames(logfile[i]);
    }

    // Create the analytics instance.
    $.each(self.List, function(key, value){
      var tool = new Analytics(logfile);
      tool.Run(value);
      self.Instance.push(tool);
    });

    function getInputNames(row){
      if (self.List.indexOf(row[3]) === -1 && row[3] !== "Name" && row[3] !== ""){
        self.List.push(row[3]);
      }
    }
  }
};


// Process Analytics //
function Analytics(logfile){

    var self = this;
    this.inputName = "";
    this.accumulative_count = 0;
    this.next_record = "";
    this.timer_pointer = 0;                                         // To keep track of where the timer should next look.
    this.up_time = new Array();
    this.down_time = new Array();
    this.off_time = new Array();

    this.Run = function(inputName){

      // Current input focus
      var input_focus = inputName;
      self.inputName = inputName;

      // Active count
      var active_count = 0;
      var first_iteration = true;

      // Accumulative count
      var accumulative_count = 0;

      // Counter
      var start_value = 0;                                          // defaults to zero after the first go
      var this_count_value = 0;
      var last_count_value = 0;
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

              // Try to create the date object
              row[0] = new Date(row[0]);
              logfile[i] = row;

              // Looking for notes
              lookForRolloverNote(row);

              // Go no further if there is no integer at counter value
              // And go no further if this isn't the input name we're looking for
              if (row[3] != input_focus || !isInt(row[5]*1)){
                if (!isInt(row[5]*1)){
                  console.log("Apparently this is not an integer: " + row[5]);
                }                
                continue;
              }

              // Keeping count
              last_count_value = this_count_value*1;
              this_count_value = row[5]*1;

              // If timer_pointer points here -- check if state is low and find the next record...
              if (self.timer_pointer >= i) {
                if (row[4]*1 === 0) next_record = findNextRecord(i, this_count_value);
              }

              // This is okay to be added to the active count
              if (this_count_value > last_count_value){

                // If this is the first active count iteration, set the active count "start_value".
                if (first_iteration == true){
                  start_value = this_count_value*1;
                  first_iteration = false;
                }

                // Add to the active count.
                addToActiveCount();
              }
              // Now we have to deal with a rollover
              else if(this_count_value < last_count_value && didEncounterNote()) {
                console.log("Detected a power down note.");
                addToAccumulativeCount();
              }
              else if(parseInt(this_count_value) < parseInt(last_count_value) && !didEncounterNote()) {
                console.log(this_count_value + "-" + last_count_value);
                console.log(row[5] + " " + row[6]);
                if (row[5]*1 == 0 && row[6]*1 == 0 || row[5]*1 == 1 && row[6]*1 == 0){
                  console.log("Detected as an authentic power down -- " + row[5] + "/" + row[6]);
                  addToAccumulativeCount();
                } else {
                  addToAccumulativeCountWithRollover();
                }  
              }            
            }
            addToAccumulativeCount();
      }

      function findNextRecord(index, this_count_value){
        for (var h = index; h < logfile.length; h ++){
          if (logfile[h][3] === self.inputName){
            // The input name matches...
            if (logfile[h][4]*1 === 1 && logfile[h][5]*1 === (this_count_value*1)+1){
              // The next count is one higher and its state is high...
              return logfile[h];
            } else if(logfile[h][5]*1 > (this_count_value*1) + 1){
              // The next count has skipped over some steps...
              var skipped_amount = (logfile[h][5]*1) - (this_count_value*1);
              console.log(logfile[h][5]);
              
              // Specify the timer pointer so we do not read the same skipped area twice.
              self.timer_pointer = (logfile[h][5]*1)+1;
              return false;
            }
          }
        } 
      }

      function isInt(n){
        if (n==="") return false;
        if(n/n===1 || n===0) return true;
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
        accumulative_count = accumulative_count*1 + active_count*1;
        self.accumulative_count = accumulative_count;
        clearVariablesForNextActiveCount();
      }

      function addToAccumulativeCountWithRollover(){
        console.log(accumulative_count);
        console.log("Start Value: " + start_value);
        console.log((start_value - 65535)*-1);
        console.log(last_count_value);
        accumulative_count = accumulative_count + ((start_value - 65535)*-1) + last_count_value;
        self.accumulative_count = accumulative_count;
        clearVariablesForNextActiveCount();
      }

      function clearVariablesForNextActiveCount(){

        // Counter //
        start_value           = this_count_value;
        active_count          = 0;
        current_value         = 0;
        last_value            = 0;
        encountered_DI_reset  = false;
        encountered_power_up  = false;

        // Timer //
        self.timer_pointer    = 0;
      }
   }
}

function TotalTimeHigh(input_name, logfile, row, fromTime, toTime){

  var self = this;
  var inputName = input_name;

  Controller();

  // The controller chooses which methods are used...
  function Controller(){
    // No time has been specified. We're going to do the whole file.
    if (fromTime == undefined){
    //console.log(row[0].getTime() + " - with state: " + row[4] + " - and count: " + row[5]);
      Iterator();  
    } else {
      logfile = parseLogfileWithConstraints();
      Iterator();  
    }
  }

  function Iterator(){
    for (var i in logfile){
      var row = logfile[i];
      var next_row = getNextRowWithInputMatch(i);
      if (row[3] !== inputName) continue;
      getTimeHigh(row, i);
    }
  }

  function parseLogfileWithConstraints(){
    parsedLogFile = logfile;
    return parsedLogFile;
  }

  function getTimeHigh(row, next_row, i){

    if (!next_row){
      return false;
    }

    if (row[4]*1 == 0 && next_row[4]*1 == 1){// && row[5]*1 == logfile[i+1][5]*1-1){
      // The state has gone from low to high appropriately.
      console.log("State went from low to high.");
      if (row[5]*1 == next_row[5]*1){
        console.log("Next row is fine");
      }
    }
  }

  function getNextRowWithInputMatch(index){
    for (var h = index+1; h < logfile.length+1; h++){
      if (logfile[h][3] !== inputName) return false;
      if (logfile[h][3] === inputName) return logfile[h];
    }

    return false;
  }
}

// Now use the analytics...//
// Contains instances...//
//InputClass.Instance
*/
</script>
