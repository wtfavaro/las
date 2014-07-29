/*
      NAMESPACE Analytics.Parser

      Using a Parser returns an array of a modified logfile.
      Multiple parsings can be used to narrow down a search.
*/

function Parser(parent){

  // Returns an array of rows for an input.
  this.Input = function(logfile, inputName){
    if (!logfile || !inputName) return false;

    var parsedLogfile = new Array();

    parent.Iterator.Start(logfile, function(Row, stopIterator){
      if (inputName === Row.Name) {
        parsedLogfile.push(Row);
      }
    }, false);

    return parsedLogfile;
  }

  // Returns an array of rows that are "high"
  this.High = function(logfile){
    if (!logfile) return false;
    
    var parsedLogfile = new Array();

    parent.Iterator.Start(logfile, function(Row, stopIterator){
      if (Row.State === 1) {
        parsedLogfile.push(Row);
      }
    }, false);
  }

  // Returns an array of rows that are "low"
  this.Low = function(logfile){
    if (!logfile) return false;
    
    var parsedLogfile = new Array();

    parent.Iterator.Start(logfile, function(Row, stopIterator){
      if (Row.State === 0) {
        parsedLogfile.push(Row);
      }
    }, false);
  }

  // Returns an array of rows between timeA and timeB
  this.Interval = function(){

  }

}
