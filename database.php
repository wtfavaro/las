<?php

/*

      database declaration.
    
*/
    //$db = new PDO("mysql:host=localhost;dbname=grouper",'root','mindmap123');
    //$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


/*

      database class.
    
*/

class Database {

  public static function match ( $query )
  {

    // Declare the global
    // database class.
    global $db;

    // Return true if there
    // is a result found.
    foreach ( $db->query($query) as $result ) {
      return true;
    }

    // If it hasn't already
    // return true, it isn't
    // going to.
    return false;
  }

}

?>
