<?php
// authorize user
$_user=new User();
//authorisation block
$l_user=@$_SESSION['l_user'];
if(!$l_user and empty($_POST['login']))
{	   
   redirect(SITE_URL);
   exit;
}

if(!$l_user and !empty($_POST['login']))
{	
	//log admin
   SanitizeHelper::sql_safe($_POST['email']);
   SanitizeHelper::sql_safe($_POST['pass']);
		
   //log admin   
   $l_user=$_user->find(-1,array("conditions"=>" email='$_POST[email]' AND pass='".md5($_POST['pass'])."' "));
  
	if(@$l_user['id']>0)
	{
		$_SESSION['l_user']=$l_user['id'];
		$l_user=$l_user['id'];        
        redirect(SITE_URL."pyrostia?".time());
	}
	else
	{		
		msg_die("Wrong username or password"); 
	}
	
}
//end authorization block
$user=$_user->find($_SESSION['l_user']);
?>