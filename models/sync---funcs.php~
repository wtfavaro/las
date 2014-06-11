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

public static function FileHasMatch( $arr_FilePack )
{
    global $db;
    $query = sprintf("SELECT * FROM sync_file WHERE path = '%s' AND name = '%s' AND software_key = '%s' AND size < '%i'",
                      $arr_FilePack["path"],
                      $arr_FilePack["name"],
                      $arr_FilePack["software_key"],
                      $arr_FilePack["size"]
                    );
    // The query is prepared.

    if (Database::match($query)){
      return true;
    } else {
      return false;
    }
    // We've determined whether or not there's a match.   
}

public static function WriteFileInfoToServer( $arr_FilePack )
{
    global $db;
    $query = "INSERT INTO file_sync ( name, size, path, date_created, date_modified, extension, software_key ) VALUES ( ?,?,?,?,?,?,? )";
    $data = array(
                    $arr_FilePack["name"],
                    $arr_FilePack["size"],
                    $arr_FilePack["path"],
                    $arr_FilePack["date_created"],
                    $arr_FilePack["date_modified"],
                    $arr_FilePack["extension"],
                    $arr_FilePack["software_key"]
            );
    // The query is prepared.

    $stmt = $db->prepare($query);
    $stmt->execute($data);
    // The query is executed.

    $db->beginTransaction(); 
    $stmt->execute( array('user', 'user@example.com')); 
    $db->commit(); 
    // Commit the query.

    return $db->lastInsertId();
    // Come up with lastInsertId.
}

}

?>
