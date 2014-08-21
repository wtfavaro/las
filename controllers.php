<?
/*

  Route for the index page.

*/
Router::path("/", function()
  {
    require PUBLIC_DIR."index.html";
  });


/*

  Route for 404 page

  If the router has failed to find a page,
  then the 404 page is required.

*/
    require PUBLIC_DIR."header.php";
    require PUBLIC_DIR."404.html";
    require PUBLIC_DIR."footer.php";

?>
