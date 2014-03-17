<?php
class Password {

  public static function hash( $str ){

    return md5($str);

  }

  public static function checkHash( $str, $hash ){
    
    return md5($str) == $hash;

  }

}
?>
