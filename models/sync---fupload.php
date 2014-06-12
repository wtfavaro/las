<?php
/*************************************
SyncFileList
**************************************/

class SyncFupload
{

private $key;
private $id;
private $file;

public function init(){
    if(!$this->_HasGlobalUploadAssets()){
        $this->Failure("Has no global assets.");
    }

    if(!$this->_FileUploaded()){
        $this->Failure("Failure to upload files.");
    }

    if(!$this->_StoreDBPointer()){
        $this->Failure("Failure to store the Database pointer.");
    }

    $this->Success();
}
private function _HasGlobalUploadAssets(){

    if(!isset($_GET["file_id"]) || !isset($_GET["software_key"]) || !SyncCore::AuthenticSoftwareKey($_GET["software_key"]) || !isset($_FILES) || !isset($_FILES["file"])) return false;
    // Find if global contains necessary variables.

    $this->key    = $_GET["software_key"];
    $this->id     = $_GET["file_id"];
    $this->file   = $_FILES["file"];
    // Assign the class properties.
    
    return true;
}
private function _FileUploaded(){

}
private function _StoreDBPointer(){

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
