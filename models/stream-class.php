<?php

class Stream {

  public function authentic(){

    // We're checking to see if the GET request is authentic.
    // If it is then we run it.
    if(isset($_GET['addr']) && isset($_GET['data'])){
      return true;
    } else {
      return false;
    }

  }

  public function capture(){

    // Now we're going to capture the data sent by the remote.
    // If we start to overload the server, we'll have to work
    // on partitioning.
    global $db;

    // Prepare the query.
    $query = sprintf("INSERT INTO packets (addr64, data, date_added) VALUES (?, ?, ?)");

    // Gather the data together.
    $data = array($_GET["addr"], $_GET["data"], gmdate("Y-m-d H:i:s"));

    // Execute the query.
    $db->prepare($query)->execute($data);

  }

}

?>
