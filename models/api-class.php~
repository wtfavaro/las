<?php

class Cloud {

  public $api;

  public function __construct(){
    $this->api = new API();
  }

}

class API {

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
        echo "This is a machine.";
        break;

      case "cell":
        echo "This is a cell.";
        break;

      case "input":
        echo "This is an input.";
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

}

?>
