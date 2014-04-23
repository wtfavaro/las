<?
/*

  Route for the index page.

*/
Router::path("/", function()
  {
    require PUBLIC_DIR."splash.php";
  });

/*

  Route for the display page.

*/
Router::path("monitor", function()
  {
    require PUBLIC_DIR."display.php";
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
Router::path("api", function()
  {
    $api = new RestAPI();
  });

/*

  API for logging into the service.

*/
Router::path("machine", function()
  {
    $machine = new Machine();
    //$machine->setSpecs(array("address" => "asdASDASDAds", "name" => "Machine 1"));
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
    require PUBLIC_DIR."header.php";
    require PRIVATE_VIEW."mach-list.php";
    require PUBLIC_DIR."footer.php";
  });

Router::path("data", function()
  {
    require PUBLIC_DIR."header.php";
    require PRIVATE_VIEW."input-list.php";
    require PUBLIC_DIR."footer.php";
  });

/*

  Send an email to a user quickly.

*/
Router::path("email", function()
  {
    header("HTTP/1.1 200 OK");

    if(isset($_POST["data"]))
      {
        $jsonArray = json_decode($_POST["data"], true);
      }
    else 
      {
        echo "0";
        exit;
      }

    $data = $jsonArray[0];

    if(isset($data["Email"]) && isset($data]["Name"]) && isset($data["Title"]) && isset($data["Body"]))
      {
        Email::Send($data["Email"], $data["Name"], $data["Title"], $data["Body"], $data["Body"]);
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
