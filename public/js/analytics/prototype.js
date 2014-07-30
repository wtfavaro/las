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

  this.CountByFiveMinutes = function(){
    // ... Do something ...
    var intervals = parent.Struct.Intervals([0,5,0,0]);
    for (var i in intervals) {
      var fiveMinIntLogfile = parent.Parser.Interval(parent.logfile, intervals[i]);
      // .. Add it to the view .. //
    }
  }

  this.CountByHour = function(){
    // ... Do something ...
    var intervals = parent.Struct.Intervals([1,0,0,0]);
    for (var i in intervals) {
      var hourIntLogfile = parent.Parser.Interval(parent.logfile, intervals[i]);
      // .. Add it to the view .. //    
    }
  }

  this.TotalHighTime = function(){
    var result = HighTime(parent, parent.logfile);
  }

}
