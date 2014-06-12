<?
/*

  Route for the index page.

*/
Router::path("/", function()
  {
    require PUBLIC_DIR."splash.php";
  });

/*

  Tools

*/

Router::path("SQLtoCSV", function()
  {
    require PUBLIC_DIR."header.php";
    require TOOLS_DIR."SQLtoCSV.php";
    require PUBLIC_DIR."footer.php";
  });

/*

  Route for the login page.

*/
Router::path("connect", function()
  {
    require PUBLIC_DIR."header.php";
    require PUBLIC_DIR."connect.php";
    require PUBLIC_DIR."footer.php";
  });

/*

  Route for the cloud page, where modifications can be made
  to your cell, machine list, etc.

*/
Router::path("cloud", function()
  {
    $cloud = new Cloud();
    require PUBLIC_DIR."cloud.php";
  });

/*

  /stream
    - The page on which data packets are sent from 
      Cell Monitor to the cloud.

*/
Router::path("stream", function()
  {
    $stream = new Stream();
    require PUBLIC_DIR."stream.php";
  });


/*

  Where a developer can view json results from the
  cloud.

*/
Router::path("spec.api", function()
  {
    // RestApi uses a class $data that houses the POST information.
    //$data = $_POST;
    new SpecAPI();
  });

Router::path("stream.api", function()
  {
    // RestApi uses a class $data that houses the POST information.
    new StreamAPI($_POST);
  });

/*

  API for logging into the service.

*/
Router::path("login", function()
  {
    $login = new LoginAPI();
  });

/*

  Create or modify your account.

*/
Router::path("account", function()
  {
    require PUBLIC_DIR."header.php";
    require PUBLIC_DIR."login.php";
    require PUBLIC_DIR."footer.php";
  });


Router::path("machines", function()
  {
    if (!isset($_SESSION))
    {

      header("Location: connect");

    }

    require PUBLIC_DIR."header.php";
    require PRIVATE_VIEW."mach-list.php";
    require PUBLIC_DIR."footer.php";
  });

Router::path("data", function()
  {
    if (!isset($_SESSION))
    {

      header("Location: connect");

    }

    require PUBLIC_DIR."header.php";
    require PRIVATE_VIEW."input-list.php";
    require PUBLIC_DIR."footer.php";
  });

Router::path("dashboard", function()
{
    if (!isset($_SESSION))
    {

      header("Location: connect");

    }

    require PUBLIC_DIR."header.php";
    require PRIVATE_VIEW."analyticsView.php";
    require PUBLIC_DIR."footer.php";
});


/****************************

VB.NET SYNCING APP

*****************************/

Router::path("sync-account", function()
{ 
  header("HTTP/1.1 200 OK");
  echo "";
  // We've prepared the page.

  if (!isset($_POST["data"]))
  { 
    echo "0";
    die;
  }
  // We've exited the method if data is not found.

  else
  {
    $data = json_decode($_POST["data"], true);
  }
  // We've turned the json data into an array for our function. 

  $keyVal = Sync_Account::Create($data);
  // We've gotten the keyVal by creating an account.

  if ($keyVal != false)
  {
    echo $keyVal;
  }
  else
  {
    echo "0";
  }
  // Now we've output the proper value.

});

Router::path("sync-fupload", function()
{
  header("HTTP/1.1 200 OK");
  print_r($GLOBALS);
});

Router::path("sync-flist", function()
{
  header("HTTP/1.1 200 OK");

  if (isset($_POST["software_key"])){
      $str_SoftwareKey = $_POST["software_key"];
  } else {
    echo "0";
    die;
  }
  // We've determined that there's a valid software_key being used.
  
  if (isset($_POST["data"]))
  {
    $fListArray = json_decode($_POST["data"], true);
    // We've created an array of our data.  
    
    for($i = 0; $i < count($fListArray); $i++)
    {
      
        $sfl = new SyncFileList();
        if($FilePack = $sfl->init($fListArray[$i])){
          $fListArray[$i] = $FilePack; 
        } else {
          $fListArray[$i] = "";
        }
    }
    // We've now iterated through each item in the array and dealt with the pack.
  }
  else
  {
    echo "0";
    die;
  }

  echo json_encode($fListArray);
  // We've now echoed the output for our VB.NET program.

});


/*********************************

  Register Mach Data

**********************************/
Router::path("reg-mach", function()
  {
    header("HTTP/1.1 200 OK");

    if (isset($_POST["data"]))
    {

      $data = $_POST["data"];

    }
    else
    {

      echo "0";
      die;

    } 

    // Get the data that can be gotten.
    if (isset($data))
      {

        $data = json_decode($_POST["data"], true);
        $data = json_decode($_POST["data"], true);
        $data[0]["inputs"] = json_decode($data[0]["inputs"], true);

      }
    else
      {

        echo "0";
        exit;

      }

    // Now declare the $mach.
    if(isset($data[0]))
      {
        $mach = $data[0];
      }
    else
      {

        echo "0";
        die;

      }

    // Now add or modify the machine.
    new Machine($mach);

  });

/*

  Send an email to a user quickly.

*/
Router::path("email", function()
  {
    header("HTTP/1.1 200 OK");

    if(isset($_POST["data"]))
      {
        $data = json_decode($_POST["data"], true);
      }
    else 
      {
        echo "0";
        exit;
      }

    if(isset($data[0]["Address"]) && isset($data[0]["Name"]) && isset($data[0]["Title"]) && isset($data[0]["Body"]))
      {
        Email::Send($data[0]["Address"], $data[0]["Name"], $data[0]["Title"], $data[0]["Body"], $data[0]["Body"]);
        echo "1";
        exit;
      }

    echo "0";

  });

/*

  A sample to show how database matching can quickly be used to
  allow custom URLs.

*/
/*Router::path(Database::match("SELECT id FROM careers WHERE permalink = '" . $_GET['_id'] . "'"), function()
  {

    // Require custom URL page, load assets and pass them to page.

  });*/


/*

  Route for 404 page

  If the router has failed to find a page,
  then the 404 page is required.

*/
    require PUBLIC_DIR."header.php";
    require PUBLIC_DIR."404.html";
    require PUBLIC_DIR."footer.php";

?>
