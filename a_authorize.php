<?php
/**
 * Celeroo Frame
 *
 * An open source rapid development framework for PHP 4.3.2 or newer
 * @package		Celeroo Frame
 * @author		Celeroo , www.celeroo.com
 * @copyright	Copyright (c) 2008, celeroo.com	
 * @link		http://www.celeroo.com/frame/frame.html
 * @since		Version 1.0
 */

// ------------------------------------------------------------------------

/**
 * Celeroo Frame admin authorize checking block
 * @package		Celeroo Frame
 * @author		Bobby Handzhiev
 * @link		http://www.celeroo.com/frame/frame.html
 */

// ------------------------------------------------------------------------
$sh=new SanitizeHelper();

//authorisation block
$logged=@$_SESSION['phpsystems'];

if(!$logged and empty($_POST['enter']))
{   
	require_once(@$path.'views/a_login.html');
	exit;
}

if(!$logged and !empty($_POST['enter']))
{
	//log admin
   $sh->paranoid($_POST['login']);
   $sh->sql_safe($_POST['password']);
		
   //log admin
   $q="SELECT id FROM $T_SETTINGS
   WHERE name='adminpass'
   AND value='$_POST[password]'";   
   $validpass=$DB->oq($q);
   
   $q="SELECT id FROM $T_SETTINGS
   WHERE name='adminlogin'
   AND value='$_POST[login]'";
   $validlogin=$DB->oq($q);
		   
   if(!$validlogin)
   {
   		msg_die("Wrong admin login ID");
   }
   
	if(!($validpass and $validlogin))
	{
   		msg_die("Wrong admin password");
    }
	
   	$_SESSION['phpsystems']=1;
   	$logged=$_SESSION['phpsystems'];   	
}
//end authorization block
?>