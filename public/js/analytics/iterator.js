function Analytics(logfile){

  var self = this;
  //var Data = new Analytics_Graph_Data();

  this.logfile = logfile;
  this.Parser = new Parser(self);
  this.Results = new Results(self);

  console.log(self);

  // Get the input names.
  function getCountByFiveSecForEachInput(logfile){
    for (var i in self.Inputs) {
      var config = {
        inputName:  self.Inputs[i],
        interval:   setupIntervalPairs([1,25,5,0])
      }
      console.log(Filter_Authentic_Count(logfile, config));
    }
  }

  function setupIntervalPairs(cnf){

  }
}

/*




*/
function Results(parent){
  this.Inputs = parent.Parser.Inputs();
  this.Activity = Analytics_Get_Activity_First_And_Last(parent.logfile);  
  this.FileTimeSpan = Analytics_Get_File_Timespan(parent.logfile);
  //this.CountByFive = getCountByFiveSecForEachInput(parent.logfile);
  this.CountByHour;
  this.TotalTime;
  this.FileDuration;
}

/*
      NAMESPACE Analytics.Parser

      Using a Parser returns an array of "rules".
      
      - For Input, an array of inputs.
      - For Intervals, an array for intervals [fromTimeMS, untilTimeMS]
*/

function Parser(parent){

  this.Inputs = function(){
    var array_of_inputs = new Array();
    Analytics_Iterator(parent.logfile, function(row){
        if (array_of_inputs.indexOf(row.Name) === -1 && row.Name !== "Name" && row.Name !== ""){
          array_of_inputs.push(row.Name);
        } 
    });
    return array_of_inputs;    
  };

  this.Intervals = function(){
    var ms = (((cnf[0]*60)*60)*1000) + ((cnf[1]*60)*1000) + (cnf[2]*1000) + cnf[3];

    var pairs = Array();
    pairs.push([new Date(), new Date(new Date().getTime()+ms)]);
    return pairs;
  };
}

/*

      Iterate through the logfile.

      params:

      logfile             - the logfile being worked on
      callback            - a callback to worker function that includes a row and an option to start the iterator
      reverse             - (bool) option to reverse the iterator
      splice_config       - an object requests the logfile be spliced before iteration

*/
function Analytics_Iterator(logfile, callback, reverse){
  var stopIterator = false;

  if (!reverse){
    for (var i in logfile){
      if (stopIterator === true) return false;
      callback(new Analytics_Row_Structure(logfile[i]), function(bool){ stopIterator = bool; });
    }
  } else {
    for (var i = logfile.length-1; i > -1; i--){
      if (stopIterator === true) return false;
      callback(new Analytics_Row_Structure(logfile[i]), function(bool){ stopIterator = bool; });
    }
  }
}


/*

    Splice the logfile into arrays that separate each array by a trait.

      - By input name
      - By high/low
      - By time interval (measured in minutes)

*/
function Filter(logfile, config, callbackRowFiltered){

  Analytics_Iterator(logfile, function(Row, stopIterator){
      Row = filterInput(Row);
      Row = filterInterval(Row);
      if (Row !== false){
        callbackRowFiltered(Row);
      }
  }, false);

  function filterInput(Row){
    if (!config.inputName) return Row;
    if (config.inputName === Row.Name || Row.Name === "") {
      return Row;
    } else {
      return false;
    }
  }

  function filterInterval(Row){
    if (!config.interval || typeof(config.interval[1]) !== "date") return Row;
    if (config.interval) {

      // - Get the Timespan (minutes)
      // - Calculate the number of intervals
      // - For loop

      var Activity = Analytics.Activity;

      var time = {
        start:      config.interval[0],
        end:        config.interval[1]
      }

      console.log(time);

      return Row;
    } else {
      return false;
    }
  }

}

/*

    Concatenate multiple logfiles into one.

*/
function Analytics_Concatenate(){
  // ...
}

/*

    Structures

*/
function Analytics_Row_Structure(row){
  var srow = row.split(",");
  this.Date     = new Date(srow[0]);
  this.Object   = srow[1];
  this.Index    = srow[2];
  this.Name     = srow[3];
  this.State    = srow[4]*1;
  this.Counter  = srow[5]*1;
  this.Timer    = srow[6]*1;
  this.Note     = srow[7];
}

function Analytics_Result_Input_Structure(){
  this.counter      = 0;
  this.timer        = 0;
  this.array_high   = new Array();
  this.array_low    = new Array();
}


function Analytics_Get_Activity_First_And_Last(logfile){

  var Activity = {
    First:  "",
    Last:   ""
  };

  Analytics_Iterator(logfile, function(Row, stopIterator){
    if(Row.State === 1) {
      Activity.First = Row.Date;
      stopIterator(true);
    }
  }, false);

  Analytics_Iterator(logfile, function(Row, stopIterator){
    if(Row.State === 1) {
      Activity.Last = Row.Date;
      stopIterator(true);
    }
  }, true);

  return Activity;
}

function Analytics_Get_File_Timespan(logfile){
  var Timespan = {
    Start: "",
    End: ""
  }

  Analytics_Iterator(logfile, function(Row, stopIterator){
    if(Row.State === 0 || Row.State === 1) {
      Timespan.Start = Row.Date;
      Timespan.Start.setHours(0,0,0);
      stopIterator(true);
    }
  }, false);

  Analytics_Iterator(logfile, function(Row, stopIterator){
    if(Row.State === 0 || Row.State === 1) {
      Timespan.End = Row.Date;
      Timespan.End.setHours(24,0,0);
      stopIterator(true);
    }
  }, true);

  return Timespan;
}

/*
  Filters
*/

/*

      Filter_Authentic_Count
      - Returns a number only, representing the accrued count. 

*/
function Filter_Authentic_Count(logfile, config){
    var Count = { Present: 0, Rewind: 0, Forward: 0 };
    var Note = { Found: false, Message: "" };
    console.log(config.interval);

    Filter(logfile, config, function(Row){
      if (Row.State === 1){
        Count.Present++;
      }
    });

    return Count.Present;
}
