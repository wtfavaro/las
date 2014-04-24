<?php

class Machine {

  protected $mach;
  protected $existing = false;

  public function __construct($mach){

    try {
      $this->mach = $mach;
      $this->__setNewMach();
    } catch(Exception $e) {
      exit;
    }

    // If this machine address exists, we
    // create a NEW RECORD. Else, we update
    // an old record.
    if (!$this->existing) {
      $this->__addMachine();
    } else {
      $this->__updateMachine();
    }
  }

  private function __setNewMach(){
    $this->existing = Database::match(sprintf("SELECT id FROM remote WHERE addr = '%s'", $this->mach["address"]));
  }

  private function __addMachine(){
      global $db;
			$query = "INSERT INTO remote (name, addr, inputs) VALUES (?,?,?)";
			$data = array($this->mach["name"], $this->mach["address"], json_encode($this->mach["inputs"]));
			$db->prepare($query)->execute($data);
      return true;
  }

  private function __updateMachine(){
      global $db;
			$query = "UPDATE remote SET name = ?, addr = ?, inputs = ? WHERE addr = ?";
			$data = array($this->mach["name"], $this->mach["address"], json_encode($this->mach["inputs"]), $this->mach["address"]);
			$db->prepare($query)->execute($data);
      return true;
  }

}

?>
