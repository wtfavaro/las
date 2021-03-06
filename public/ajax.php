<?php

// Include the database file.
require_once '../database.php';

// Include all the model classes
// that will be accessible by AJAX.
foreach(glob("../models/*.php") as $file){
  require $file;
}


if (method_exists($_POST['class'], $_POST['method'])){
  // Declare the class and method.
  $class = $_POST['class'];
  $method = $_POST['method'];

  // Call the method.
  if(isset($_POST['args']) && !isset($_POST["args"]["callback"])){
    ajaxResponse($class::$method($_POST['args']));
  } elseif(!isset($_POST['args']) && !isset($_POST["args"]["callback"])) {
    ajaxResponse($class::$method());
  }

  // Call the method for if a callback is expected.
  if(isset($_POST["args"]["callback"]) && isset($_POST["args"])){
    $class::$method($_POST["args"]);
  } elseif (isset($_POST["args"]["callback"]) && !isset($_POST["args"])){
    $class::$method();
  }

} 

function ajaxResponse($method_response){
  if ($method_response === true) echo 1;
  if ($method_response === false) echo 0;
}
?>
