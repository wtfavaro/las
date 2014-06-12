<?php

class SyncCore
{

/*
Database Table: freepoint.sync_file

+---------------+-----------+------+-----+---------+----------------+
| Field         | Type      | Null | Key | Default | Extra          |
+---------------+-----------+------+-----+---------+----------------+
| id            | int(7)    | NO   | PRI | NULL    | auto_increment |
| name          | char(64)  | YES  |     | NULL    |                |
| size          | int(10)   | YES  |     | NULL    |                |
| path          | char(255) | YES  |     | NULL    |                |
| date_created  | char(128) | YES  |     | NULL    |                |
| date_modified | char(128) | YES  |     | NULL    |                |
| extension     | char(16)  | YES  |     | NULL    |                |
| software_key  | char(64)  | YES  |     | NULL    |                |
+---------------+-----------+------+-----+---------+----------------+
*/

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


/*************************************
SyncFileList
**************************************/

class SyncFileList
{

private $FilePack         = "";
private $ServerFileInfo   = "";
private $intPrimaryId     = "";

public function init( $arr_FilePack )
{

  // Save the filepack locally.
    $this->FilePack = $arr_FilePack;
    unset($arr_FilePack);

  // If there is no record, and we can't create one, then we return false.
    if(!_DoesRecordExist($this->FilePack)){
      if(!_AddNewFileRecord($this->FilePack)){
        return false;
      }
    }

  // If the record is up-to-date, then we remove it from the
  // file array by indicating false to the caller function.
    if(_IsRecordUpToDate($this->FilePack, $this->ServerFileInfo)){
      return false;
    }

  // If the record isn't up-to-date, then we have to prepare
  // the cell for return.
    _PrepFileInfoForReturn($this->ServerFileInfo);

  // We're now ready to return the filepack.
    return $this->FilePack;

}

private function _DoesRecordExist($FilePack){  
  // Check if this record exists.
    global $db;
    $query = sprintf("SELECT * FROM sync_file WHERE path = '%s' AND name = '%s' AND software_key = '%s' LIMIT 1",
                      $arr_FilePack["path"],
                      $arr_FilePack["name"],
                      $arr_FilePack["software_key"]
                    );

    if($fetch = Database::FetchAll($query) && isset($fetch[0])){
      $row = $fetch[0];
    } else {
      return false;
    }

  // Save the row for the record and return.
    if(isset($row) && isset($row["id"])){
      $this->ServerFileInfo = $row;
      return true;
    } else {
      return false;
    }
}
private function _AddNewFileRecord($FilePack){
  // Add a new file record.
    global $db;
    $query = "INSERT INTO file_sync ( name, size, path, date_created, date_modified, extension, software_key ) VALUES ( ?,?,?,?,?,?,? )";
    $data = array(
                    $FilePack["name"],
                    $FilePack["size"],
                    $FilePack["path"],
                    $FilePack["date_created"],
                    $FilePack["date_modified"],
                    $FilePack["extension"],
                    $FilePack["software_key"]
            );
    $db->prepare($query)->execute($data);

  // Check if the record exists and return.
    return $this->_DoesRecordExist($FilePack);
}
private function _IsRecordUpToDate($FilePack, $ServerFileInfo){
  If($FilePack["size"] == $ServerFileInfo["size"] && isset($ServerFileInfo["file_pointer"]) && $ServerFileInfo["file_pointer"] <> ""
      && $ServerFileInfo["file_pointer"] <> "NULL"){
    return true;
  } else {
    return false;
  }
}
private function _PrepFileInfoForReturn($ServerFileInfo){
  $this->FilePack["id"] = $ServerFileInfo["id"];
}
}

?>
