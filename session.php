<?php

class Session
{

public static function Set($auth)
{

// Sets the cookie with which the browser is identified.
//
//

setcookie(
  "londonappshoppe",
  $auth,
  time() + 3600
);

}


// Looks for a freepoint cookie
// Turns it into a SESSION.
//

public static function Load()
{

  // If we already have a session, then we back out.
  if (isset($_SESSION) && isset($_SESSION["email"]))
  {

    return true;

  }

  if(array_key_exists("freepoint", $_COOKIE))
  {

    // Get the Auth Key.
    $authKey = $_COOKIE["freepoint"];

    // Load the info.
    $actData = Database::fetchAll("SELECT * FROM account WHERE auth = '$authKey'");

    // Check if we found an account.
    if (isset($actData[0]))
    {
      if (!isset($actData[0]["auth"]))
      {
        return false;
      }
    }
    else
    {
      return false;
    }

    // Start the session.
    session_start();
      $_SESSION["email"] = $actData[0]["email"];
      $_SESSION["password"] = $actData[0]["password"];
      $_SESSION["machines"] = Getters::__getMachines($authKey);
    session_write_close();
  }
}

}


?>
