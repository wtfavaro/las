<?php

class SyncProperties {

  public static function getTotalServerFileLoad(){
    global $db;
    $query = "SELECT size from sync_file WHERE file_pointer IS NOT NULL";
    $results = Database::FetchAll($query);
    
    $total_size = 0;

    foreach($results as $result){
      $total_size = $total_size + $result["size"];
    }

    echo $total_size;
    return "json";
  }

}

?>
