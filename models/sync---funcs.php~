<?php

public class SyncCore
{

public static function AuthenticSoftwareKey($str_SoftwareKey)
{
    global $db;
    $query = sprintf("SELECT * FROM sync_company WHERE software_key = '%s'", $str_SoftwareKey);
    // Now the query is ready.
  
    if (Database::match($query)){
      return true;
    } else {
      return false;
    }
    // Now we've determined if there's a match and returned to the calling function.
}

}

?>
