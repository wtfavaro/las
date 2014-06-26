<?php

  class FPSecure {
    
    public static function VerifyUser($data){

      // Prepare the database call.
      global $db;
      $result = Database::FetchAll(sprintf("SELECT software_key FROM sync_account WHERE email = '%s' AND password LIKE '%s'", $data["email"], md5($data["password"])));

      print_r(md5($data["password"]));

      // Isolate software key
      if (isset($result[0]["software_key"]) && isset($result[0]["software_key"])){
        $softwareKey = $result[0]["software_key"];
      }

      // Return the value.
      if (isset($softwareKey) && $softwareKey){
        echo $softwareKey;
      }

      echo 0;
    }

    public static function GetSoftwareKey($data){
      return $softwarekey;
    }
  }

?>
