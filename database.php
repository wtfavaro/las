<?php

/*

      database declaration.
    
*/
    $db = new PDO("mysql:host=localhost;dbname=freepoint",'root','mindmap123');
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


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
    $results = $db->query($query);

    if($results){
      foreach ( $results as $result ) {
        return true;
      }
    }

    return false;
  }

  public static function FetchAll($query)
  {
    
    // Declare the global database class.
    global $db;

    // Create an array.
    $resultArray = array();

    // Get all of the results.
    if($resultset = $db->query($query)){
      foreach($resultset as $result){
        $resultArray[] = $result;
      }
    }

    // Return the result array.
    return $resultArray;

  }

}

?>
