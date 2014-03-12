<?php

// Peel away a package.
class Package {

  public static function Each($packageArr, $callback){

    // Test if this is an array.
    if(!is_array($packageArr)){
      return false;
    }

    // Send the callback foreach package.
    foreach($packageArr as $pck){
      $callback($pck);
    }

  }

  public static function MostRecent($query){
    
    // Return the database results.
    return Database::FetchAll($query);
  }

}

?>
