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

    $_POST["email"] = "wtfavaro@hotmail.com";
    $_POST["name"] = "William T. Favaro";
    $_POST["title"] = "This has been routed through the server";
    $_POST["body"] = "Hey <b>this has been <u>styled.</u></b>";

    if(isset($_POST["email"]) && isset($_POST["name"]) && isset($_POST["title"]) && isset($_POST["body"]))
      {
        require_once("/mail/send-mail.php");
        Email::Send("wtfavaro@hotmail.com", "William Favaro", $_POST["title"], $_POST["body"], $_POST["body"]);
      }

  }

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
