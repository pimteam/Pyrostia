<?php
/*
automatic loading of helper classes and functions
*/
require_once("helpers/benchmark.php");
require_once("helpers/email.php");
require_once("helpers/encrypt.php");
require_once("helpers/cache.php");
require_once("helpers/fileutil.php");
require_once("helpers/image.php");
require_once("helpers/sanitize.php");
require_once("lib/ajax.php");
require_once("lib/db.php");
require_once("lib/html.php");
require_once("lib/js.php");
require_once("lib/functions.php");
require_once("lib/misc.php");
require_once("models/basic.php");
require_once("helpers/relation.php");

// handle url routes
celeroo_routes();
?>