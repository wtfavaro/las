<?php


// Router
class Router {

  public static function path ( $incoming, $outgoing ) {

    // If incoming is true, then
    // it has been routed via matching.
    if ( $incoming === true )
    {
      $outgoing(); 
    }

    // Make sure it knows how
    // to handle the index page.
    if ( !isset($_GET['_id']) && $incoming==="/" )
    {
      $outgoing();
      die();
    }

    // Just die if there is no
    // incoming _id
    if ( !isset($_GET['_id']) ) {
      die();
    }

    // Handle a traditional route.
    if ( $incoming === $_GET['_id'] )
    {
      $outgoing();
      die();
    }
  }

}

?>
