<?php

class User
  {

    public static function make($email, $password)
      {
        global $db;

        $db->prepare(
          "INSERT INTO account (email, password, date_added) VALUES (?, ?, ?)"
        )->execute(
          array( $email, $password, gmdate() )
        );
      }

    public static function delete($email, $password)
      {
        global $db;

        $db->prepare(
          "DELETE FROM account WHERE email = ? AND password = ?"
        )->execute(
          array( $email, $password )
        )

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

  }
