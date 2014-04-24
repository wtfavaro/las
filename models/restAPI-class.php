<?php

class RestAPI {

  public function __construct(){
      $data = array(
        "get" => "machines",
        "softkey" => "ASD"
      );

    if (isset($data["get"]) && isset($data["softkey"]) && $data["get"] = "machines")
      {
        $result = DATABASE::fetchAll(sprintf("SELECT * FROM remote WHERE softkey = '%s'", $data["softkey"]));
        echo json_encode($result);
      }
  }

}

/*

The RESTAPI classes are pretty simple.

1. The user requests data.
2. That request is parsed into a string query and sent to the database.
3. The results from the database are cleaned up, made readable, and displayed in json.

*/
/*
class RestAPI extends ManageJSON {

  // Validate the query, build the query, send the query.
  public function __construct(){
    if(isset($_GET['auth'])){

      // Build the query.
      $this->query_parts = new QueryBuildr();
      
      // Send the query.
      $this->query();
    } 
  }

  // Check if we have permission to view each machine.
  public function validate(){ 
  }

  public function query(){
    $query = sprintf("SELECT * FROM packets WHERE %s ORDER BY date_added DESC", implode($this->query_parts->SQL_WHERE, " AND "));
//  print_r($query);

    $results = DATABASE::FetchAll($query);

    // Add the inputs to the result set.
    $results = $this->add_inputs_to_resultset($results);

    echo json_encode($results);
  }
}

class QueryBuildr {

  public $SQL_WHERE = array();

  // Figure out the query by the environments
  // $_GET variables.
  public function __construct(){

    $this->add_where($_GET);

  }

  public function add_where($paramArr){

    if(!count($paramArr) > 0){
      $this->SQL_WHERE[] = "1=1";
      return false;
    }

    // Isolate the WHERE columns and values and create
    // an array of SQL-query-ready strings.
    foreach($paramArr as $column => $value){
      switch($column){
        case("mach"):
          $this->SQL_WHERE[] = "addr64 = '" . $value . "'";
          break;
        case("onFocus"):
          $this->SQL_WHERE[] = "date_added > '" . $value . "'";
          break;
        case("endFocus"):
          $this->SQL_WHERE[] = "date_added < '" . $value . "'";
      }
    }

  }

}

class ManageJSON {

  public function add_inputs_to_resultset($results){

    for($i = 0; $i < count($results)-1; $i++){
    
      // Grab the data from the result row.
      $data = $results[$i]["data"];

      // Strip data into columns.
      $dataArr = explode("-", $data);

      // Go to next if this packet doesn't have
      // valid data.
      if(!isset($dataArr[1])){
        continue;
      }

      // Get the timer values.
      $timer1 = ($dataArr[4] * 255) + $dataArr[5];
      $timer2 = ($dataArr[8] * 255) + $dataArr[9];
      $timer3 = ($dataArr[12] * 255) + $dataArr[13];
      $timer4 = ($dataArr[16] * 255) + $dataArr[17];

      // Get the counter values.
      $counter1 = ($dataArr[2] * 255) + $dataArr[3];
      $counter2 = ($dataArr[6] * 255) + $dataArr[7];
      $counter3 = ($dataArr[10] * 255) + $dataArr[11];
      $counter4 = ($dataArr[14] * 255) + $dataArr[15];
    
      // Add the inputs to the result set.
      $results[$i]["data"] = array(
        "INPUT1" => array("timer" => $timer1, "counter" => $counter1),
        "INPUT2" => array("timer" => $timer2, "counter" => $counter2),
        "INPUT3" => array("timer" => $timer3, "counter" => $counter3),
        "INPUT4" => array("timer" => $timer4, "counter" => $counter4),
      );
    }

    // Return the results to be used by the constructor.
    return $results;
  }

}
*/
//
// [ ] auth=dasdWEgdgWEReweeDDDDGDFbfdf@qwd
//
// Auth, on its own, will return a list of machines and their inputs.
//

//
// [x] machine={addr64} defaults to {"ALL"}
//
// Specifying a machine name will return its inputs by name.
//

//
// [ ] input={name} defaults to {"ALL"}
//
// Can return data from a specific input.
//

//
// [ ] mode={"DATA"} defaults to {"ID"}
//
// On DATA, will return raw info.
// On iD, will return the names of hardware.
//

//
// [ ] onFocus={"YYYY-mm-dd"} defaults to {"0000-00-00"}
//
// This sets the initial focus point, where the data collection will begin.
//

//
// [ ] endFocus={"YYYY-mm-dd"} defaults to {"2099-00-00"}
//
// This sets the end focus point, after which data is no longer collected.
//

//
// [ ] limit={INT}
//
// This specifies how many entries we want to pull, from the onFocus point.
//

?>
