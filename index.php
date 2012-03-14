<?php
ob_start();
session_start();
// includes
require_once("lib/functions.php");
$_context=get_context();
$_context->set("cache", false);
$DB=get_db_connection();
require_once('inc/defines.inc.php');
require_once('autoload.php');
$l_user=@$_SESSION['l_user'];
// end includes

$_GET['action']=!empty($_GET['action'])?$_GET['action']:"main";

if(!is_file("controllers/$_GET[action].php"))
{   
    msg_die("Wrong input parameter.");
}

require("controllers/$_GET[action].php");

require("views/master.html");
?>