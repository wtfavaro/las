<?php

/*

  reuire_all is a core fn used to require
  all files within a directory -- useful
  for the model directory, where all files
  need to be included for AJAX.

*/
function require_all($path)
  {
    // Get an array of the files
    // within the folder path.
    $files = glob($path);

    if(is_array($files))
      {
        foreach($files as $file)
          {
            require $file;
          }
      }
  }

/*
  The template class is used to display
  views and require the header/footer.
*/

class Template
{
  public static function header()
    {
      require_once PUBLIC_DIR.'header.php';
    }

  public static function footer()
    {
      require_once PUBLIC_DIR.'footer.php';
    }

  public static function add_view($path)
    {
      require_once PRIVATE_VIEW.$path.'.php';
    }
}
