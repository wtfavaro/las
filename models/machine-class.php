<?php

class Machine {

  public function __construct(){
    if (isset($_POST["inputs"])){
      print_r($GLOBALS);
    }
  }

}

/*
class Machine {

	public static function GetSpecs($mach){
		
		// Request the information.
		return Database::fetchAll(sprintf("SELECT * FROM remote WHERE addr = '%s'", $mach['address']));
		
	}
	
	public function SetSpecs($mach){
		
    // Validate
    if (!isset($mach["name"]) || !isset($mach["address"])){
      return false;
    }

		// Global database object
		global $db;

    // Check if we need to UPDATE or INSERT
    if (Database::match(sprintf("SELECT id FROM remote WHERE addr = '%s'"), $mach["address"])){
      SpecUpdate();  
    } else {
      SpecNew();
    }
  }

  private function SpecUpdate(){

		// We build and commit the query.
		if (!isset($mach['inputs'])){
			$query = "UPDATE remote SET name = ? AND addr = ?";
			$data = array($mach["name"], $mach["address"]);
			$db->prepare($query)->execute($data);
      return true;
		}

    return false;

  }

  private function SpecNew(){

		// We build and commit the query.
		if (!isset($mach['inputs'])){
			$query = "INSERT INTO remote (name, addr, inputs) VALUES (?,?,?)";
			$data = array($mach["name"], $mach["address"], json_encode($mach["inputs"]));
			$db->prepare($query)->execute($data);
      return true;
		}

    return false;

  }
}*/

?>
