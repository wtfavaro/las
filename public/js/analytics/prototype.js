function Prototype (parent) {

  var coreIsFinishedLoading = setInterval(function(){
    var ready = true;
    if (parent.Results === undefined){
      ready = false;
    } else {
      for (var i in parent.Results)
      {
        if (parent.Results[i] === undefined){
          ready = false;
        }
      }
      if (ready === true){
        RunPrototypes();
      }
    }
  }, 100);

  function RunPrototypes(){
    clearInterval(coreIsFinishedLoading);
    for (var fnIndex in parent.Prototype) {
      parent.Prototype[fnIndex]();
    }
  }

  this.mainGUI = function(){
    $("#lblFirstActivityTime").html(time_format(parent.Results.Activity.First));
    $("#lblLastActivityTime").html(time_format(parent.Results.Activity.Last));
    $("#lblTotalActivityTime").html(time_difference(parent.Results.Activity.First, parent.Results.Activity.Last));
    $("#lblTotalReportTime").html(time_difference(parent.Results.FileTimeSpan.Start, parent.Results.FileTimeSpan.End));
  };

  this.TotalCount = function(){
    Purify(parent, parent.logfile, function(results){
      // Add this to view.
      for (var index in results){
        var $div = $("#structInputSample").clone();
        $div.css({"display": "block"}).appendTo("#sectionOverviewText");
        $div.id = "";
        $div.find("small#lblOverviewInputName").html(index);
        $div.find("b#lblOverviewInputCount").html(results[index].Counter);
      }
    });
  };

  this.CountByFiveMinutes = function(){

    // Get the interval struct array and build the overview graph.
    var intervals = parent.Struct.Intervals([0,5,0,0]);
    build_activity_graph();

    // Loop through the struct and generate 5 minute logfiles
    // Send those logfiles to fill the activity graph.
    var intervalLogfileArray = new Array();
    for (var i in intervals) {
      intervalLogfileArray.push(parent.Parser.Interval(parent.logfile, intervals[i]));
    }

    build_activity_graph(intervals, intervalLogfileArray);

    function build_activity_graph(intStruct, logfileArray){
      // Both of these are the same length, so run a single for loop and piece them together.
    }

    /*function fill_activity_graph(logfile){
        Purify(parent, logfile, function(results){
          // Add this to view.
          for (var index in results){
            //
          }
        });
    } */
  }

  this.CountByHour = function(){
    // ... Do something ...
    var intervals = parent.Struct.Intervals([1,0,0,0]);
    for (var i in intervals) {
      var logfile = parent.Parser.Interval(parent.logfile, intervals[i]);
        Purify(parent, logfile, function(results){
          // Add this to view.
          for (var index in results){
            //
          }
        }); 
    }
  }

}


// Misc functions.
function time_format(d) {
    hours = format_two_digits(d.getHours());
    minutes = format_two_digits(d.getMinutes());
    seconds = format_two_digits(d.getSeconds());
    return hours + ":" + minutes + ":" + seconds;
}

function format_two_digits(n) {
    return n < 10 ? '0' + n : n;
}

function time_difference(d1, d2) {
  var remainder = d2.getTime() - d1.getTime();

  if (format_two_digits(parseInt((remainder/(1000*60*60))%24)) === "00") remainder = remainder - 1000;

  var seconds = format_two_digits(parseInt((remainder/1000)%60))
        , minutes = format_two_digits(parseInt((remainder/(1000*60))%60))
        , hours = format_two_digits(parseInt((remainder/(1000*60*60))%24))

  return hours + ":" + minutes + ":" + seconds;
}
