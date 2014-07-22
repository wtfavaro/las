function Analytics(logfile){

  var self = this;
  var Iterator = new Analytics_Iterator(logfile);
  var Data = new Analytics_Graph_Data();

  // Get inputs
  

}

// Iterator
function Analytics_Iterator(logfile, callback){
    for (var i in logfile){
      callback(new Analytics_Row_Structure(logfile[i]));
    }
}

function Analytics_Splice(logfile, from_time, to_time){
    var new_logfile;
    Analytics 
}

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

function Analytics_Input_Data(){
  this.input_name   = "";
  this.timer_high   = "";
  this.timer_low    = "";
  this.counter      = "";
}

function Analytics_GetInputs(logfile){
  var array_of_inputs = new Array();
    Analytics_Iterator(logfile, function(){
      
    }
  return array_of_inputs;
}
