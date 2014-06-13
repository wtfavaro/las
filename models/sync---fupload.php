<?php
/*************************************
SyncFileList
**************************************/

class SyncFupload
{
private $UPLOAD_PATH;
private $key;
private $id;
private $file;
private $filename;

public function init(){
    if(!$this->_HasGlobalUploadAssets()){
        $this->Failure("Has no global assets.");
    }

    if($oldfilehash = $this->_HasOldFile($this->id)){
      $this->_RemoveOldFile($oldfilehash);
    }

    if(!$this->_FileUploaded($this->file, $this->filename)){
        $this->Failure("Failure to upload files @ " . $this->UPLOAD_PATH);
    }

    if(!$this->_StoreDBPointer($this->id, $this->filename)){
        $this->Failure("Failure to store the Database pointer.");
    }

    $this->Success();
}
private function _HasGlobalUploadAssets(){

    if(!isset($_GET["file_id"]) || !isset($_GET["software_key"]) || !SyncCore::AuthenticSoftwareKey($_GET["software_key"]) || !isset($_FILES) || !isset($_FILES["file"])) return false;
    // Find if global contains necessary variables.

    $this->key      = $_GET["software_key"];
    $this->id       = $_GET["file_id"];
    $this->file     = $_FILES["file"];
    $this->filename = Software::key();
    $this->UPLOAD_PATH  = "/var/www/freepoint/public/uploads/";
     // Assign the class properties.
    
    return true;
}
private function _FileUploaded($file, $filename){
     
    $uploadpath = $this->UPLOAD_PATH;

    if (file_exists($uploadpath)) {
      return false;
    }
    // If the file exists, we return false.

    if (move_uploaded_file($file["tmp_name"],
      $uploadpath . $filename)){
      return true;
    }
    // If the file is moved, we return true.
}
private function _HasOldFile($id){
    global $db;
    $query = sprintf("SELECT file_poiner FROM sync_file WHERE id = '%s'", $id);
    $fp = Database::FetchAll($query);
    if(isset($fp[0]) && isset($fp[0]["file_pointer"])){
      return $fp[0]["file_pointer"];   
    } else {
      return false;
    }
}
private function _RemoveOldFile($oldfilehash){
  $filename     = $this->UPLOAD_PATH . $oldfilehash;
  if(file_exists($filename)){
    unlink($filename);
  }
}
private function _StoreDBPointer($id, $filename){
    global $db;
    $query = "UPDATE sync_file SET file_pointer = ? WHERE id = ?";
    $data = array($filename, $id);
    $db->prepare($query)->execute($data);
}
private function Success(){
    echo "1";
    die;
}
private function Failure($msg){
    echo json_encode(array("error"=>$msg));
    die;
}

}

?>
