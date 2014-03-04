<?php

class Cloud {

  public $api;

  public function __construct(){
    $this->api = new API();
  }

}

class Schema {
  public $database;

  public function __construct(){
    $this->database = new StdClass;
    $this->database->cells = array(
      "name",
      "software_key",
      "date_added"
    );
    $this->database->machines = array(
      "name",
      "software_key",
      "cell_id",
      "date_added"
    );
    $this->database->inputs = array(
      "name",
      "software_key",
      "machine_id",
      "date_added"
    );
    $this->database->remotes = array(
      "addr64",
      "name",
      "date_added"
    );
  }
}

class API extends Schema {

  public function validHttpRequest(){
    if(isset($_GET['on'])){
      return true;
    } else {
      return false;
    }
  }

  public function controller(){
    switch($_GET['on']){

      case "software":
        $this->software();
        break;

      case "machine":
        $this->machine();
        break;

      case "remote":
        $this->remote();
        break;

      case "cell":
        $this->cell();
        break;

      case "input":
        $this->input();
        break;

      case "alert":
        echo "This is an alert request.";
        break;

      default:
        echo "This is a faulty request.";
        break;

    }
  }

  public function software(){
    if(!isset($_GET['get'])){
      die();
    } 

    switch($_GET['get']){
      case "key":
        echo Software::register();
        break;

      default:
        echo "false";
        break;
    }
  }

  // Actions being taken on a cell
  // go here.
  public function cell(){
    global $db;
    $action = "";
    $table = "";
    $query = "";

    // If no key, safely die.
    if(!isset($_GET['key'])){
      die();
    }

    // Determine action taken.
    $action = $this->getAction();

    // Define target table.
    $table = "cells";

    // Build the query.
    $query = $this->buildQuery($action, $table);

    // Add data.
    $data = $this->addData($action);

    // Execute query.
    $db->prepare($query)->execute($data);
  }

  public function remote(){

    // The remote function is spaghetti -- being
    // created quickly so we can show a demo.
    // Will need improvements in the long-term.
    // Check to see if this is a valid request.
    if(!isset($_GET['addr'])){
      return false;
    }

    global $db;
    $action = "";
    $table = "";
    $query = "";

    // Determine action taken.
    $action = $this->getAction();   

    // Define the target table.
    $table = "remotes"; 

    // Build the query.
    $query = $this->buildQuery($action, $table);

    // Add data.
    switch($action){
      case "put":
        $data = array($_GET['addr'], $_GET['put'], date("Y-m-d H:i:s"));
        break;
      default:
        $data = $this->addData($action);
        break;
    }

    // Execute query.
    $db->prepare($query)->execute($data);

  }

  public function machine(){
    global $db;
    $action = "";
    $table = "";
    $query = "";

    // If no key, safely die.
    if(!isset($_GET['key'])){
      die();
    }

    // Determine action taken.
    $action = $this->getAction();

    // Define target table.
    $table = "machines";

    // Build the query.
    $query = $this->buildQuery($action, $table);

    // Add data.
    switch($action){
      case "put":
        $data = array($_GET['put'], $_GET['key'], "1", date("Y-m-d H:i:s"));
        break;
      default:
        $data = $this->addData($action);
        break;
    }

    // Execute query.
    $db->prepare($query)->execute($data);
  }

  // Action to be taken on an input
  public function input(){
    global $db;
    $action = "";
    $table = "";
    $query = "";

    // If no key, safely die.
    if(!isset($_GET['key'])){
      die();
    }

    // Determine action taken.
    $action = $this->getAction();

    // Define target table.
    $table = "inputs";

    // Build the query.
    $query = $this->buildQuery($action, $table);

    // Add data.
    switch($action){
      case "put":
        $data = array($_GET['put'], $_GET['key'], "1", date("Y-m-d H:i:s"));
        break;
      default:
        $data = $this->addData($action);
        break;
    }

    // Execute query.
    $db->prepare($query)->execute($data);
  }

  // Figure out which action is being
  // worked on, based on the _GET variable.
  private function getAction(){
    if(isset($_GET['rename'])){
      return "rename";
    } else if(isset($_GET['put'])){
      return "put";
    } else if(isset($_GET['remove'])){
      return "remove";
    } else {
      return false;
    } 
  }

  private function buildQuery($action, $table){
    switch($action){
      case "rename":
        return "UPDATE $table SET name=? WHERE name=? AND addr64=?";  
        break;
      case "remove":
        return "DELETE FROM $table WHERE name=? AND addr64=?";
        break;
      case "put":
        return "INSERT INTO $table " . $this->buildInsert($table);
        break;
      default:
        return false;
    }
  }

  private function addData($action){
    switch($action){
      case "rename":
        return array($_GET['rename-to'], $_GET['rename'], $_GET['addr']);
        break;
      case "remove":
        return array($_GET['remove'], $_GET['addr']);
        break;
      case "put":
        return array($_GET['put'], $_GET['key'], date("Y-m-d H:i:s"));
        break;
    }
  } 

  private function buildInsert($table){
    $quemrk = array();
    $query = "";

    // Create a array of quemrks
    for($i=0; $i<count($this->database->$table); $i++){
      $quemrk[] = "?";
    }

    // Build the query. Can be optimized
    // as sprintf later.
    $query .= "(";
    $query .= implode($this->database->$table, ", ");
    $query .= ") VALUES (";
    $query .= implode($quemrk, ", ");
    $query .= ")";

    return $query;
  }
}

?>
