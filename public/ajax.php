<?php

// Include the database file.
require_once '../database.php';

// Include all the model classes
// that will be accessible by
// AJAX.
foreach(glob("../models/*.php") as $file)
  {
    require $file;
  }


if (method_exists($_POST['class'], $_POST['method']))
  {

    // Declare the class and method.
    $class = $_POST['class'];
    $method = $_POST['method'];

    // Call the method.
    $class::$method($_POST);

  }


class Account
  {
    public static function make($params)
      {
        // Declare global database class.
        global $db;

        // Prepare the query.
        $query = "INSERT INTO user (username, password) VALUES (?, ?)";
        $data = array($params["uname"], $params["password"]);

        // Execute the query and return
        // the result.
        if ( $db->prepare($query)->execute($data) )
          {

            echo json_encode(array
              (
                "status"=>  "success", 
                "text"  =>  "Your account has been created!"
              ));

            die();

          }

      }

    public static function exists($params)
      {

        // Declare the variable.
        $username = $params['uname'];

        // Prepare the query.
        if (Database::match("SELECT id FROM user WHERE username = '$username'"))
          {

            echo json_encode(array
              (
                "status"=>  "error", 
                "text"  =>  "This username is taken!"                
              ));

            die();

          } else {

            echo json_encode(array
              (
                "status"=>  "success"               
              ));

            die();

          }
      }
  } 

?>
