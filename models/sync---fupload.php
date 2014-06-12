<?php
/*************************************
SyncFileList
**************************************/

class SyncFupload
{

private const UPLOAD_PATH = PUBLIC_DIR . "uploads/";
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
        $this->Failure("Failure to upload files.");
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
    // Assign the class properties.
    
    return true;
}
private function _FileUploaded($file, $filename){
     
    $uploadpath = UPLOAD_PATH;

    if (file_exists($filename)) {
      return false;
    } else {
      move_uploaded_file($file["tmp_name"],
      $uploadpath . $filename);
    }

}
private function _HasOldFile($id){
    global $db;
    $query = ("SELECT file_poiner FROM sync_file WHERE id = '%s'", $id);
    $fp = Database::FetchAll($query);
    if(isset($fp[0]) && isset($fp[0]["file_pointer"])){
      return $fp[0]["file_pointer"];   
    } else {
      return false;
    }
}
private function _RemoveOldFile($oldfilehash){
  $filename     = UPLOAD_PATH . $oldfilehash 
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
