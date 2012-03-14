<?php
if(!empty($_POST['ok']))
{
    if($_POST['gender']!='abv475') exit;
    
    $subject=$_POST['subject'];
    $message=nl2br($_POST['message'])."<br><br>From $_POST[email]";
    send_mail(SENDER, SENDER, $subject, $message);
    
    alert("Thank you for contacting us. We will get back to you soon.");
}

$pagetitle="Contact Us";
$view="views/contact.html";
?>