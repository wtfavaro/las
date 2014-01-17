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
    echo "Valid request being controlled.";
  }

}

?>
