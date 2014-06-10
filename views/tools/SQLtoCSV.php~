<div class="container">

<div class="row">
  <h2><small>Tool:</small> <b>SQL2CSV</b></h2>
</div>

<div class="row text-center" style="padding: 15px; border-radius: 5px; border: 1px solid #999; background: #bbb;">
  <input id="selMachAddress" type="text" style="border-radius: 3px; border: 1px solid #999; padding: 5px; font-size: 1.3em;" placeholder="Machine Address" />
  <input id="selSoftwareKey" type="text" style="border-radius: 3px; border: 1px solid #999; padding: 5px; font-size: 1.3em;" placeholder="Software Key" />
  <button id="btnGenerateCSVReport" class="btn btn-default" style="margin-top: 0px; border: 1px solid #999;" disabled="disabled">Generate CSV</button>
</div>

<div class="row text-center">
  <h3><small id="alertUserLoading"></small></h3>
</div>

</div>

<script type="text/javascript">

// Shorten each input pointer.
var $inputs = {
  key:      $("#selSoftwareKey"),
  address:  $("#selMachAddress"),
  btn:      $("#btnGenerateCSVReport")
};

// Pre-Populate values.
$("#selSoftwareKey").val( "ff26613f42cd95857cdf815fcb78782e" );

// Disable button until input fields have value.
$("#selSoftwareKey, #selMachAddress").on("keyup", function()
{
  if ($inputs.key.val() && $inputs.address.val())
  {
    $("#btnGenerateCSVReport").removeAttr("disabled");
  } else {
    $("#btnGenerateCSVReport").attr("disabled", "disabled");
  }
});

// The user wants to generate a CSV file from the server.
$("#btnGenerateCSVReport").on("click", function()
{

  genFunc( "" );

});

var Gen = {
  Settings:
  {
    dataCollectedFromServer:  [],
    dataAggFromServer:        [],
    searchMoreRecords:        window.genFunc,
    logFile:                  []
  }
};

// The function that aggregates the packets into one array (Gen.Settings.dataCollectedFromServer)
function genFunc( startDate )
{

  new generateCSVReport( $inputs.address.val(), $inputs.key.val(), startDate,
    function( data, hasMoreRecords, lastDateFound )
    {

      // Add the data found on this cycle

        Gen.Settings.dataCollectedFromServer = Gen.Settings.dataCollectedFromServer.concat(data);

      // Recall _self if we need more data
      if ( hasMoreRecords ) {
        Gen.Settings.searchMoreRecords( lastDateFound );
      } else {
        if (Gen.Settings.dataCollectedFromServer.length > 0)


          //Gen.Settings.dataCollectedFromServer.sort(function(a,b){return a.data["CYCLE"].counter-b.data["CYCLE"].counter});
          console.log(convertToLogFile(Gen.Settings.dataCollectedFromServer));

      }

      // Show the user how many records have been found
      $("#alertUserLoading").text(Gen.Settings.dataCollectedFromServer.length + " records found.");
    }
  );
}

// The function which grabs the data from the server
function generateCSVReport( machAddress, softwareKey, startDate, foundResultsCallback )
{

  var data = {
    "get":      "packets",
    "softkey":  softwareKey,
    "address":  machAddress,
    "limit":    "500",
    "time":     {}
  };

  // Add date to data as constraint within the query
  if (startDate){
    data.time.start = startDate;
  }

  $.ajax({
    type: "POST",
    url: "stream.api",
    data: data,
    success: function(response){

      if ( response[0] && response[499] ) {
        foundResultsCallback(response, true, response[499].date_added);
      } else if ( response[0] && !response[499] ) {
        foundResultsCallback( response, false, "" );
      } else {
        alert("There are no results.");
      }
    },
    dataType: "json"
  });
}

// Convert to LOG data file
function convertToLogFile( dataPack )
{

  var logHeader = "Date,Name,NewStat,NewCt,NewTm,Note\n",
      svrData = dataPack,
      logFile = Array(),
      totalRecords = dataPack.length;

  function logStruct(){
    this.date     = "";
    this.nm       = "";
    this.state    = "";
    this.count    = "";
    this.tm       = "";
    this.note     = "";
    this.id       = "";
  }

  function convertObjectToString(obj){
    var arr = [obj.date, obj.nm, obj.state, obj.count, obj.tm, obj.note];
    return arr.join(",");
  }
  
  for (var i = 0; i < svrData.length; i++) {
    
    var line = { cur: svrData[i], last: svrData[i-1] },
        foundPurposeForPacket = false,
        logLine = new logStruct();

    // Determine if there's even a point to continuing
    // If there's no last packet, then no comparison can be made.
    if (typeof(line.last) === "undefined" || typeof(line.cur) === "undefined") continue;

    // Check if this input is different from the last
    // Compare this and last input for the purpose of
    // assessing what action has occured.
    for (var nameOfInput in line.cur.data){

      // Determine which input has changed.
      if(line.cur.data[nameOfInput].counter > line.last.data[nameOfInput].counter){
        logLine.date    = line.cur.date_added;
        logLine.nm      = nameOfInput;
        logLine.state   = "1";
        logLine.count   = line.cur.data[nameOfInput].counter;
        logLine.tm      = line.cur.data[nameOfInput].timer;
        logLine.note    = "DI Change";
        logLine.id      = line.cur.id;

        foundPurposeForPacket = true;

        logFile.push(logLine);
        continue;
      }

    }

    // Run conditions if NO INPUT has changed -- determine if it is a DI reset or a low state.

    if (!foundPurposeForPacket) {

      var isReset = true,
          isStateLow = true;

      for (var nameOfInput in line.cur.data) {
        
        // The input data is above zero, which means a reset is impossible.
        if (line.cur.data[nameOfInput].counter > 0 && line.cur.data[nameOfInput].timer > 0) {
          isReset = false;
        }

      }

      if (isReset) {

        logLine.date    = line.cur.date_added;
        logLine.object  = "";
        logLine.state   = "";
        logLine.count   = "";
        logLine.tm      = "";
        logLine.note    = "DI Reset";
        logLine.id      = line.cur.id;

        logFile.push(logLine);
        continue;

      } else if (isStateLow && !isReset && logFile.length > 0 && typeof(line.cur.data[logFile[logFile.length-1].nm]) === "object") {

        if (logFile[logFile.length-1].state == "0") continue;
        
        logLine.date    = line.cur.date_added;
        logLine.nm      = logFile[logFile.length-1].nm;
        logLine.state   = "0";
        logLine.count   = line.cur.data[logFile[logFile.length-1].nm].counter;
        logLine.tm      = line.cur.data[logFile[logFile.length-1].nm].timer;
        logLine.note    = "DI Change";
        logLine.id      = line.cur.id;

        logFile.push(logLine);
        continue;
      }

    }

    $("#alertUserLoading").text("Analyzing ("+i+" of "+totalRecords+")");
  }

  function joinRecords(){

    logFile.sort(function(a,b){return a.id-b.id});

    for (var a in logFile) {
      logFile[a] = convertObjectToString(logFile[a]);
    }

    return logFile.join("\n");

  }

  return logHeader + joinRecords();
}
</script>
