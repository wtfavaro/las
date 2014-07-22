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

public static function ForceUpdate($args){
  if(isset($args["software-key"])){
    global $db;
    $date = new DateTime();
    $query = "INSERT INTO sync_force (software_key, date_added) VALUES (?,?)";
    $data = array($args["software-key"], $date->format("Y-m-d H:i:s"));
    $write = $db->prepare($query)->execute($data);

    // Return values
    if (!isset($args["json"])){
      if ($write) {
        echo 1;
      } else {
        echo 0;
      }
    } else {
      if ($write) {
        return true;
      } else {
        return false;
      }
    }
  }
}

public static function HasPingRequest($args){
  // sync-force will notify the software that a force request has been made
  // and to initialize the update immediately.
  
  if (isset($args["software-key"])){

    // Fetch the record.
    global $db;
    $query = sprintf("SELECT * FROM sync_force WHERE software_key = '%s'", $args["software-key"]);
    $match = Database::match($query);

    // Echo the output.
    if ($match){
      return true;
    } else {
      return false;
    }
  }
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

  // Acknowledge this sync request.
  $this->AddSyncRequestDateTime($this->FilePack);

  // If the path contains "backup", we don't want to upload it.
  if (stripos($this->FilePack["Path"],"backup") !== FALSE){
    return false;
  }

  // If there is no record, and we can't create one, then we return false.
    if(!$this->_DoesRecordExist($this->FilePack)){
      if(!$this->_AddNewFileRecord($this->FilePack)){
        return false;
      }
    }

  // If the record is up-to-date, then we remove it from the
  // file array by indicating false to the caller function.
    if($this->_IsRecordUpToDate($this->FilePack, $this->ServerFileInfo)){
      return false;
    }

  // If the record isn't up-to-date, then we have to prepare
  // the cell for return.
    $this->_PrepFileInfoForReturn($this->ServerFileInfo);

  // Update the record now.
    $this->_UpdateRecord($this->FilePack, $this->ServerFileInfo);

  // We're now ready to return the filepack.
    return $this->FilePack;

}

private function _DoesRecordExist($FilePack){  
  // Check if this record exists.
  // *** working here.
    global $db;
    $query = sprintf("SELECT * FROM sync_file WHERE name = '%s' AND path = '%s' AND software_key = '%s' LIMIT 1",
                      $FilePack["Name"],
                      addslashes($FilePack["Path"]),
                      addslashes($FilePack["SoftwareKey"])
                    );

    $fetch = Database::FetchAll($query);

    // Grab the fetch in $row.
    if(isset($fetch[0])){
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
    $query = "INSERT INTO sync_file ( name, size, path, date_created, date_modified, extension, software_key ) VALUES ( ?,?,?,?,?,?,? )";
    $data = array(
                    $FilePack["Name"],
                    $FilePack["Size"],
                    $FilePack["Path"],
                    $FilePack["DateCreated"],
                    $FilePack["DateModified"],
                    $FilePack["Extension"],
                    $FilePack["SoftwareKey"]
            );
    $db->prepare($query)->execute($data);

  // Check if the record exists and return.
    return $this->_DoesRecordExist($FilePack);
}
private function _IsRecordUpToDate($FilePack, $ServerFileInfo){
  // If the file contains keywords.
  if (stripos($FilePack["Name"],".machlist") !== FALSE || stripos($FilePack["Name"],".config") !== FALSE || stripos($FilePack["Name"],".mach") !== FALSE) {
    return false;
  }

  if($FilePack["Size"] == $ServerFileInfo["size"] && $FilePack["Path"] == $ServerFileInfo["path"] && isset($ServerFileInfo["file_pointer"]) && $ServerFileInfo["file_pointer"] <> ""
      && $ServerFileInfo["file_pointer"] <> "NULL"){
    return true;
  } else {
    return false;
  }
}
private function _UpdateRecord($FilePack, $ServerFileInfo){
    global $db;

    if ($FilePack["Size"] < $ServerFileInfo["size"]){
        // If size is now less...
        $query = "UPDATE sync_file SET path = ?, date_modified = ? WHERE id = ?";
        $data = array(
                        $FilePack["Path"],
                        $FilePack["DateModified"],
                        $FilePack["id"]
                );
        return false;
    } else {
    // If size is equal or more...
        $query = "UPDATE sync_file SET size = ?, path = ?, date_modified = ? WHERE id = ?";
        $data = array(
                        $FilePack["Size"],
                        $FilePack["Path"],
                        $FilePack["DateModified"],
                        $FilePack["id"]
                );
    }

    // Execute.
    return $db->prepare($query)->execute($data);
}
private function _PrepFileInfoForReturn($ServerFileInfo){
  $this->FilePack["id"] = $ServerFileInfo["id"];
}
private function AddSyncRequestDateTime($FilePack){
  $dtNow = new DateTime();
  $tz_ny = new DateTimeZone("America/Toronto");
  $dtNow->setTimezone($tz_ny);
  $mysqlDateTime = $dtNow->format("Y-m-d H:i:s");

  global $db;
  $query = "UPDATE sync_company SET last_updated = ? WHERE software_key = ?";
  $data = array($mysqlDateTime, $FilePack["SoftwareKey"]);

  // Execute the query.
  return $db->prepare($query)->execute($data);
}
}

?>
