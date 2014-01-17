<?php

class Software {

  public static function register() {
    global $db;

    // Generate key.
    $key = Software::key();

    // Build the query.
    $query = "INSERT INTO distribution (product_key) VALUES (?)";
    $data = array($key);

    // Place into the database.
    $db->prepare($query)->execute($data);
  }

  public static function key() {
    return md5(base64_encode(date('Y-m-d H:i:s')));
  }

}

?>
