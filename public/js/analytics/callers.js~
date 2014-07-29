function Library (Core) {

  /*

    Get the first and last active date object.

  */
  this.Activity = function(logfile){

    var Activity = {
      First:  "",
      Last:   ""
    };

    Core.Iterator.Start(logfile, function(Row, stopIterator){
      if(Row.State === 1) {
        Activity.First = Row.Date;
        stopIterator(true);
      }
    }, false);

    Core.Iterator.Start(logfile, function(Row, stopIterator){
      if(Row.State === 1) {
        Activity.Last = Row.Date;
        stopIterator(true);
      }
    }, true);

    return Activity;
  }

  /*

      Get the timespan of the file.

  */
  this.Timespan = function(logfile){
    var Timespan = {
      Start: "",
      End: ""
    }

    Core.Iterator.Start(logfile, function(Row, stopIterator){
      if(Row.State === 0 || Row.State === 1) {
        Timespan.Start = Row.Date;
        Timespan.Start.setHours(0,0,0);
        stopIterator(true);
      }
    }, false);

    Core.Iterator.Start(logfile, function(Row, stopIterator){
      if(Row.State === 0 || Row.State === 1) {
        Timespan.End = Row.Date;
        Timespan.End.setHours(24,0,0);
        stopIterator(true);
      }
    }, true);

    return Timespan;
  }

  this.countByFive = function(){
    var intervals = Core.Struct.Intervals();
    
    return count_array;
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

      Filter(logfile, config, function(Row){
        if (Row.State === 1){
          Count.Present++;
        }
      });

      return Count.Present;
  }

}


/*****************************************************************

Documentation

******************************************************************/

// How to get each parse inputs quickly

/*
  for (var i in this.Results.Inputs){
    var parsedLogfile = this.Parser.Input(Core.logfile, Core.Results.Inputs[i]);
  }
*/
