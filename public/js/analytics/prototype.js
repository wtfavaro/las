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
    $("#loadOverlay").animate({ "opacity": "0" }, 1000, function(){ $(this).css({ "z-index": "-1" }); });
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

      uptimePercentageChart(results);
    });

    function uptimePercentageChart(data){
      var msDayTimespan = parent.Results.FileTimeSpan.End.getTime() - parent.Results.FileTimeSpan.Start.getTime();
      for (var i in data) {
        console.log(i);
      }
    }
  };

  this.CountByFiveMinutes = function(){

    // Get the interval struct array and build the overview graph.
    var intervals = parent.Struct.Intervals([0,5,0,0]);

    // Loop through the struct and generate 5 minute logfiles
    // Send those logfiles to fill the activity graph.
    var intervalLogfileArray = new Array();
    for (var i in intervals) {
      intervalLogfileArray.push(parent.Parser.Interval(parent.logfile, intervals[i]));
    }

    build_activity_graph(intervals, intervalLogfileArray);

    function build_activity_graph(intStruct, logfileArray){

      var generatedColumnsArray = new Array('x');
      var generatedInputArrays = new Array();

      // Generate the input arrays -- this will create the array of count
      // per input, per five minutes on the graph.
      for (var i in parent.Results.Inputs) {
        generatedInputArrays.push(new Array(parent.Results.Inputs[i]));
      }


      // Step through the Interval Structure for this graph.
      // Label the X-axis.
      // Get the count and timer for each tick.
      for (var i in intStruct) {

        // Add ticks to the graph on X-axis.
        generatedColumnsArray.push(intStruct[i][0].getHours() + ":" + format_two_digits(intStruct[i][0].getMinutes()));
        
        Purify(parent, logfileArray[i], function(results){
          for (var index in results){
            for (var id in generatedInputArrays){
              if (generatedInputArrays[id][0] == index) {
                generatedInputArrays[id].push(results[index].Counter);
              }
            }
          }
        });

      }

      // Add to the generated columns array.
      var totalColumnsArray = new Array();
      totalColumnsArray.push(generatedColumnsArray);
      for (var i in generatedInputArrays) {
        totalColumnsArray.push(generatedInputArrays[i]);
      }

      var chart = c3.generate({
          bindto: '#chart-downtime',
          data: {
              x : 'x',
              columns: totalColumnsArray,
              groups: [
                  ['download', 'loading']
              ],
              type: 'area'
          },
          axis: {
              x: {
                  type: 'category', // this needed to load string x value
                  show: true,
                  tick: {
                    culling: {
                      max: 9              
                    }
                  }
              },
          },
          zoom: {
                  enabled: true
                },
          regions: [
            /*{axis: 'x', start: 0, end: 3, class:'chartDowntime'},
            {axis: 'x', start: 11, end: 16, class:'chartDowntime'},
            {axis: 'x', start: 44, end: 57, class:'chartDowntime'},
            {axis: 'x', start: 71, end: 79, class:'chartDowntime'},
            {axis: 'x', start: 84, end: 93, class:'chartDowntime'}*/
          ],
          point: {
            show: false
          }
      });
    }
  }

  this.CountByHour = function(){
    // Get the interval struct array and build the overview graph.
    var intervals = parent.Struct.Intervals([1,0,0,0]);

    // Loop through the struct and generate 5 minute logfiles
    // Send those logfiles to fill the activity graph.
    var intervalLogfileArray = new Array();
    for (var i in intervals) {
      intervalLogfileArray.push(parent.Parser.Interval(parent.logfile, intervals[i]));
    }

    build_overview_graph(intervals, intervalLogfileArray);

    function build_overview_graph(intStruct, logfileArray) {

      var generatedColumnsArray = new Array('x');
      var generatedInputArrays = new Array();

      // Generate the input arrays -- this will create the array of count
      // per input, per five minutes on the graph.
      for (var i in parent.Results.Inputs) {
        generatedInputArrays.push(new Array(parent.Results.Inputs[i]));
      }

      // Step through the Interval Structure for this graph.
      // Label the X-axis.
      // Get the count and timer for each tick.
      for (var i in intStruct) {

        // Add ticks to the graph on X-axis.
        generatedColumnsArray.push(intStruct[i][0].getHours() + ":" + format_two_digits(intStruct[i][0].getMinutes()));
        
        Purify(parent, logfileArray[i], function(results){
          for (var index in results){
            for (var id in generatedInputArrays){
              if (generatedInputArrays[id][0] == index) {
                generatedInputArrays[id].push(results[index].Counter);
              }
            }
          }
        });

      }

      // Add to the generated columns array.
      var totalColumnsArray = new Array();
      totalColumnsArray.push(generatedColumnsArray);
      for (var i in generatedInputArrays) {
        totalColumnsArray.push(generatedInputArrays[i]);
      }

      // Add the graph
      var chart = c3.generate({
          bindto: '#chart',
          data: {
              x : 'x',
              columns: totalColumnsArray,
              groups: [
                  ['download', 'loading']
              ],
              type: 'bar'
          },
          axis: {
              x: {
                  type: 'category'
              }
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
