<?php

// If not a valid api call, die
// Else run the api controller

if($stream->authentic()){
  $stream->capture();
} else {
  echo json_encode(array(
    "result"=>"0",
    "errormsg"=>"The request could not be authenticated."
  ));
}

?>

