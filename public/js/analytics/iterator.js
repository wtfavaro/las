
/*

  Function: Analytics

  When you instantiate the Analytics class, you instantly have a full API at your disposal. It is purely an
  accessor class, allowing you to pull in all the data from this logfile into your custom program. Remember to
  pass the instance of Analytics into your graphing functions -- name the argument "Core" or "Parent".

*/

function Analytics(logfile){

  var self = this;
  this.logfile = logfile;
  logfile = "";

  // Loading the essential instances.
  this.Iterator = new Iterator(self);
  this.Parser = new Parser(self);
  this.Struct = new Struct(self);

  // Using the built-in library to generate results.
  this.Library = new Library(self);
  this.Results = new Results(self);

  // Addon Functions -- built on the page.
  this.Prototype = new Prototype(self);

  console.log(self);
}

/*

      Results
      - Aggregates the results that from our built-in functions
      - this.Inputs = an array of input names found in the file
      - this.Activity = first and last activity found in the file
      - this.FileTimeSpan = the time scope, from when to when.
      - this.CountByFive = the counter by five minutes.
      - this.CountByHour = the counter by hour.

*/
function Results(parent){
  this.Inputs = parent.Struct.Inputs(parent.logfile);
  this.Activity = parent.Library.Activity(parent.logfile);  
  this.FileTimeSpan = parent.Library.Timespan(parent.logfile);
}


/*

      Iterate through the logfile.

      _function_

      this.Start 

      _params_

      logfile             - the logfile being worked on
      callback            - a callback to worker function that includes a row and an option to start the iterator
      reverse             - (bool) option to reverse the iterator

*/
function Iterator(parent){

  this.Start = function(logfile, callback, reverse){
    var stopIterator = false;
    var skipIterator = false;

    if (!reverse){
      for (var i in logfile){
        if (stopIterator === true) return false;
        if (skipIterator === true) continue;
        callback(new parent.Struct.Row(logfile[i]), function(bool){ stopIterator = bool; }, i, function(bool){ skipIterator = bool });
      }
    } else {
      for (var i = logfile.length-1; i > -1; i--){
        if (stopIterator === true) return false;
        if (skipIterator === true) continue;
        callback(new parent.Struct.Row(logfile[i]), function(bool){ stopIterator = bool; }, i, function(bool){ skipIterator = bool });
      }
    }
  };
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
function Struct(parent){

  this.Row = function(row){
    if (typeof(row) === "object") return row;
    var srow = row.split(",");
    this.Date     = new Date(srow[0]);
    this.Object   = srow[1].trim();
    this.Index    = srow[2].trim();
    this.Name     = srow[3].trim();
    this.State    = srow[4]*1;
    this.Counter  = srow[5]*1;
    this.Timer    = srow[6]*1;
    this.Note     = srow[7].trim();
  }

  this.Inputs = function(logfile){
    var array_of_inputs = new Array();
    parent.Iterator.Start(logfile, function(row){
        if (array_of_inputs.indexOf(row.Name) === -1 && row.Name !== "Name" && row.Name !== ""){
          array_of_inputs.push(row.Name);
        } 
    });
    return array_of_inputs;    
  };

  this.Intervals = function(cnf){
    var ms = (((cnf[0]*60)*60)*1000) + ((cnf[1]*60)*1000) + (cnf[2]*1000) + cnf[3];

    var newDate = parent.Results.FileTimeSpan.Start;
    var oldDate;
    var pairs = Array();

    while (newDate < parent.Results.FileTimeSpan.End){
      oldDate = newDate;
      newDate = new Date(newDate.getTime() + ms);
      if (newDate <= parent.Results.FileTimeSpan.End) pairs.push([oldDate, newDate]);
    }

    return pairs;
  };

}


