<?php
// includes
session_start();
ob_start();
require_once("lib/functions.php");
$_context=get_context();
$_context->set("cache", false);
$DB=get_db_connection();
require_once('inc/defines.inc.php');
require_once('autoload.php');
require_once("a_authorize.php");
// end includes

$_GET['action']=!empty($_GET['action'])?$_GET['action']:"main";

if(!is_file("controllers/a_$_GET[action].php"))
{	
	msg_die("Wrong input parameter.");
}
require("controllers/a_$_GET[action].php");
require_once('views/a_master.html');
?>