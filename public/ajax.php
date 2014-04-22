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
  if(isset($_POST['args'])){
    ajaxResponse($class::$method($_POST['args']));
  } else {
    ajaxResponse($class::$method());
  }
} 

function ajaxResponse($method_response){
  switch($method_response){
    case true:
      echo 1;
      break;
    case false:
      echo 0;
      break;
  }
}

?>