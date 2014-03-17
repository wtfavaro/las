<?php

// Load constants.
require("constants.php");

// Load core.
require("core.php");

// Load the password class.
require("password.php");

// Load the database class.
require("database.php");

// Load the routing class.
require("router.php");

// Requie all the model classes.
require_all("../models/*.php");

// Load the controllers.
require("controllers.php");

?>
