<?php

class Sync_Account {


public static function Create($cred) {
  
  if (!isset($cred["company_name"]) || !isset ($cred["protected_path"])) return false; 

  // We now know that we have enough credentials to continue.


  $cred["software_key"] = Software::key();

  // We now have a software key generated for this user.


  global $db;

  $query = "INSERT INTO sync_company (name, software_key, protected_folder_path) VALUES (?, ?, ?)";
  
  $data = array($cred["company_name"], $cred["software_key"], $cred["protected_path"]);

  $db->prepare($query)->execute($data);

  // Now we've created the account.

  return true;

}

}

?>
