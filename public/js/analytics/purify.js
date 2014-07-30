function Purify(Core, logfile) {

  var self = this;

  var Values = {};

  console.log("Purifying");

  // Get a list of the inputs.
  var inputs = Core.Struct.Inputs(Core.logfile);

  // Create an indicators object that will be used to
  // keep track of the iterator.
  var Indicators = {
    firstIteration: true,
    Reset: false,
    Rollover: false,
    IterationsSinceReset: 0,
    Counter: {
      Last: 0,
      Current: 0
    },
    Active: {
      Start: 0
    },
    Accumulative: {
      Count: 0
    }
  }

  // For loop through the inputs -- parse by input and iterate through each block.
  for (var i in inputs){

    var parsedLogfile = Core.Parser.Input(logfile, inputs[i]);

    var HighTime = new HighTime(Core, parsedLogfile);

    Core.Iterator.Start(parsedLogfile, function(Row, stopIterator, index, fnSkip){    

      // Keep track of RESETS.
      Indicators.IterationsSinceReset ++;

      // Find out if there is a NOTE or OBVIOUS POWER UP/RESET present.
      if (Row.Note === "DI Reset" || Row.Note === "Remote Power Up" || Row.Timer === 0 && Row.Counter === 0 || Row.Timer === 0 && Row.Counter === 1) {
        console.log("Before iteration cutoff");
        if (Indicators.firstIteration === false && Indicators.IterationsSinceReset > 2){
          Indicators.Reset = true;
          Indicators.IterationsSinceReset = 0;
        }
      }

      if (Row.Counter === NaN) fnSkip();

      // Set first iteration conditions.
      if (Indicators.firstIteration === true) {
        if (Row.State === 0) {
          Indicators.Active.Start = Row.Counter;
        } else if (Row.State === 1) {
          Indicators.Active.Start = Row.Counter - 1;
          Indicators.firstIteration = false;
        }
      }

      // Get the LAST and CURRENT counter value.
      Indicators.Counter.Last = Indicators.Counter.Current*1;
      Indicators.Counter.Current = Row.Counter*1;

      // See if there is a notable discrepency (a skip by more than one).
      if (((Indicators.Counter.Last / 5) * 4) > Indicators.Counter.Current && Indicators.Reset === false){
        if (Indicators.firstIteration === false && Indicators.IterationsSinceReset > 2){
            Indicators.Rollover = true;
        }
      }

      // Manage a ROLLOVER.
      if (Indicators.Reset === false && Indicators.Rollover === true) {
        console.log("A rollover happened." + " - " + Indicators.Counter.Last + " - " + Indicators.Counter.Current);
        Indicators.Accumulative.Count = Indicators.Accumulative.Count + Indicators.Counter.Last + ((Indicators.Active.Start - 65535)*-1);
      }

      // Manage a RESET.
      if (Indicators.Reset === true) {
        console.log("A reset happened.");
        console.log(Row);
        Indicators.Accumulative.Count = Indicators.Accumulative.Count + Indicators.Counter.Last - Indicators.Active.Start;
      }

      // Reset Note indicators.
      Indicators.Rollover = Indicators.Reset = false;

    }, false);

    // At the end of the input loop, clean up.
    Indicators.Accumulative.Count = Indicators.Accumulative.Count + Indicators.Counter.Current - Indicators.Active.Start;
    console.log(inputs[i] + " - " + Indicators.Accumulative.Count);
    Values[inputs[i]] = Indicators.Accumulative.Count;
    ResetIndicators();
  }

function ResetIndicators(){
  Indicators.Reset = false;
  Indicators.Rollover = false;
  Indicators.firstIteration = true;
  Indicators.Counter.Last = Indicators.Counter.Current = Indicators.Counter.Next = 0;
  Indicators.Active.Start = 0;
  Indicators.Accumulative.Count = 0;
  Indicators.IterationsSinceReset = 0;
}

  return Values;
}


// Calculate High Time -- Used By Purify To Supply Complimentary Results //
function HighTime(Core, Row){
/*
  this.LastRow = this.Row;
  this.Row = Row;
  console.log(this);
*/
}
