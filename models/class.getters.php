<?php


class Getters
{

public static function __getMachines($softkey)
{

  // Get the machines.
  $machines = DATABASE::fetchAll(sprintf("SELECT * FROM remote WHERE softkey = '%s' ORDER BY name", $softkey));

  // Get the inputs for each.
  for ($i = 0; $i < count($machines); $i++)
  {
    
    $machines[$i]["inputs"] = json_decode($machines[$i]["inputs"], true);

  }

  return $machines;
  
}

}
