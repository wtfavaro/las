<h1>Not prepared to generate a report.</h1>

<?php

if (!isset($_POST["path"])){
  echo "No file found.";
} else {
  $LogFile = new LogFile($_POST["path"]);
  //print_r($LogFile->contents);
}

class LogFile {

public $contents = array();

public function __construct($path){

  $error = false;

  $handle = @fopen($path, "r");

  if ($handle) {
      while (($buffer = fgets($handle, 4096)) !== false) {
          $this->contents[] = explode(",", $buffer);
      }
      if (!feof($handle)) {
          $error = true;
      }
      fclose($handle);
  }

  if (!$error){

    unset($this->contents[0]);

    // Convert the date string at column[0] into a datetime object.
    $this->contents = $this->_AddDateTimeToContent($this->contents);

  }
}

private function _AddDateTimeToContent($content){

  foreach ($content as $row) {
    $timestamp = explode(" ", $row[0]);

    // Convert Timestamp into Array.
    $time = array(
      "date"   => "",
      "time"   => "",
      "ms"     => "",
      "suffix" => ""
    );

    $time["date"] = explode("/", $timestamp[0]);
    $time["time"] = explode(":", $timestamp[1]);
    $time["suffix"] = $timestamp[2];
    $ms = explode(".", $time["time"][2]);
    $time["time"][2] = $ms[0];
    $time["ms"] = $ms[1];
    
    print_r($time);
  }

  return $content;

}

}

?>
