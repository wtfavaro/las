<?php

  class FPSecure {
    
    public static function VerifyUser($data){

      // Prepare the database call.
      global $db;
      $result = Database::FetchAll(sprintf("SELECT software_key FROM sync_account WHERE email = '%s' AND password = '%s'", $data["email"], md5($data["password"])));

      // Isolate software key
      if (isset($result[0]["software_key"]) && isset($result[0]["software_key"])){
        $softwareKey = $result[0]["software_key"];
      }

      // Return the value.
      if (isset($softwareKey) && $softwareKey){
        FPSecure::AddKeyToSession($softwareKey);        
        echo $softwareKey;
      } else {
        echo "0";
      }

    }

    public static function AddKeyToSession($softwareKey){
        session_start();
        setcookie("FreePointSecureDashboard", $softwareKey, time()+3600);
    }

    public static function CreateAccount($data){
      if (!isset($data["email"]) || !isset( $data["password"] ) || !isset($data["firstname"]) || !isset($data["lastname"]) ||
          !isset($data["software_key"])){
        echo json_encode(array("error"=>"Invalid parameters -- account could not be saved."));
        return false;
      }

      if (!SyncCore::AuthenticSoftwareKey($data["software_key"])){
        echo json_encode(array("error"=>"Software key not valid."));
        return false;
      }

      global $db;

      $query  = "INSERT INTO sync_account (email, password, firstname, lastname, software_key) VALUES (?,?,?,?,?)";
      $data   = array($data["email"],md5($data["password"]),$data["firstname"],$data["lastname"],$data["software_key"]);

      if($db->prepare($query)->execute($data)){
        echo 1;
        return true;
      }
    }

    public static function GetSoftwareKey($data){
      return $softwarekey;
    }
  }

?>
