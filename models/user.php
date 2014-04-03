<?php

class User
  {

    public static function make($params)
      {
        if (!method_exists("Password", "hash")){
          include '../password.php';
        }

        global $db;

        $db->prepare(
          "INSERT INTO account (email, auth, password, date_added) VALUES (?, ?, ?, ?)"
        )->execute(
          array( $params["email"], Software::key(), Password::hash($params["password"]), date("yyyy-MM-dd") )
        );
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

    public static function match($email, $password)
      {
        $account = DATABASE::fetchAll("SELECT * FROM account WHERE email = '$email' LIMIT 1");

        if(isset($account['password'])){
          return Password::hash($password) == $account['password'];
        } else {
          return false;
        }
      }

    public static function email_exists($params){
      $query = sprintf("SELECT id FROM account WHERE email = '%s'", $params['email']);

      if (Database::match($query)){
        return true;
      } else {
        return false;
      }
    }

  }
