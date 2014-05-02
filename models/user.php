<?php

class User
  {

    public static function make($params)
      {
        // Incude the Password class.
        if (!method_exists("Password", "hash")){
          include '../password.php';
        }

        // Check if this request is valid.
        if (!filter_var($params["email"], FILTER_VALIDATE_EMAIL)){
          echo "Not a valid email.";
          return false;
        }

        // Include the global Database class.
        global $db;

        // Insert user into the database.
        $db->prepare(
          "INSERT INTO account (email, auth, password, date_added) VALUES (?, ?, ?, ?)"
        )->execute(
          array( md5($params["email"]), Software::key(), md5($params["password"]), date("yyyy-MM-dd") )
        );

        return true;
      }

    public static function delete($email, $password)
      {
        global $db;

        $db->prepare(
          "DELETE FROM account WHERE email = ? AND password = ?"
        )->execute(
          array( $email, $password )
        );

      }

    public static function match($params, $md5 = false)
      {

        if (!isset($params["password"]) || !isset($params["email"]))
        {
          exit;
        }

        if(!$md5)
        {
          $email = md5($params["email"]);
          $password = md5($params["password"]);
        }
        else
        {
          $email = $params["email"];
          $password = $params["password"];
        }   

        // Fetch from the database.
        $account = DATABASE::fetchAll("SELECT * FROM account WHERE email='$email' AND password='$password' LIMIT 1");

        if(isset($account[0]['auth']))
        {    
          Session::Set($account[0]['auth']);
          return $account[0]['auth'];
        } else {
          return "0";
        }
      }

    public static function email_exists($params){
      $query = sprintf("SELECT id FROM account WHERE email = '%s'", md5($params['email']));

      if (Database::match($query)){
        return true;
      } else {
        return false;
      }
    }

  }
