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
        echo "No Global Assets.";
        $this->Failure();
    }

    if(!$this->_FileUploaded()){
        echo "File couldn't be uploaded.";
        $this->Failure();
    }

    if(!$this->_StoreDBPointer()){
        echo "Couldn't create DB Pointer";
        $this->Failure();
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
private function Failure(){
    echo "0";
    die;
}

}

?>