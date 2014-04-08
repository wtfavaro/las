<?php

class LoginAPI {

  public function __construct() {
    $this->validRequest();
    $this->lookupAccount();
  }

  private function validRequest() {
    if (isset($_GET['user']) && isset($_GET['password'])){
      die;
    }
  }

  private function lookupAccount($email, $password) {
    return User::match($email, $password);
  }

}


?>
