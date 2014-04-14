<?php

class LoginAPI {

  public function __construct() {
    $this->validRequest();
    $matchFound = $this->lookupAccount($_GET['user'], $_GET['password']);

    // Echo the return value.
    echo $matchFound;
  }

  private function validRequest() {
    if (!isset($_GET['user']) || !isset($_GET['password'])){
      die;
    }
  }

  private function lookupAccount($email, $password) {
    return User::match($email, $password);
  }

}


?>
