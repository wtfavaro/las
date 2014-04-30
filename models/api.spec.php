<?php

class SpecAPI {

  public function __construct(){
      $data = array(
        "get" => "machines",
        "softkey" => "ASD"
      );

    if (isset($data["get"]) && isset($data["softkey"]) && $data["get"] = "machines")
      {
        $result = DATABASE::fetchAll(sprintf("SELECT * FROM remote WHERE softkey = '%s'", $data["softkey"]));
        echo json_encode($result);
      }
  }

}

//
// [ ] auth=dasdWEgdgWEReweeDDDDGDFbfdf@qwd
//
// Auth, on its own, will return a list of machines and their inputs.
//

//
// [x] machine={addr64} defaults to {"ALL"}
//
// Specifying a machine name will return its inputs by name.
//

//
// [ ] input={name} defaults to {"ALL"}
//
// Can return data from a specific input.
//

//
// [ ] mode={"DATA"} defaults to {"ID"}
//
// On DATA, will return raw info.
// On iD, will return the names of hardware.
//

//
// [ ] onFocus={"YYYY-mm-dd"} defaults to {"0000-00-00"}
//
// This sets the initial focus point, where the data collection will begin.
//

//
// [ ] endFocus={"YYYY-mm-dd"} defaults to {"2099-00-00"}
//
// This sets the end focus point, after which data is no longer collected.
//

//
// [ ] limit={INT}
//
// This specifies how many entries we want to pull, from the onFocus point.
//

?>
