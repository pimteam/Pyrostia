<?php
$_user=new User();
$error=false;

if(!empty($_SESSION['l_user']))
{
    redirect(SITE_URL."pyrostia");
}

// this is the main "sales" page with login/register buttons
if(!empty($_POST['register']))
{
    try
    {
        $uid=$_user->add($_POST);
        
        // login user
        $_POST['login']=1;
        require("authorize.php");
        $_SESSION['flash']="Thank you! You have been successfully registered.";        
        redirect(SITE_URL."pyrostia/");
    }
    catch(Exception $e)
    {
        $error=true;
        $err_msg=$e->getMessage();
    }
}

$pagename="Your Weekly Menu Everywhere With You";
$view="views/main.html";
?>