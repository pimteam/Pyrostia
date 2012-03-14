<?php
# this is the script that actually displays the calendar with meals etc
require("authorize.php");
$_grocery=new Grocery();

if(!empty($_POST['buy']))
{
	// sanitize
	$_POST['week']=intval($_POST['week']);
	$_POST['month']=intval($_POST['month']);
	$_POST['year']=intval($_POST['year']);

	$status=$_POST['status']?1:0;

	SanitizeHelper::sql_safe($_POST['item']);
	$q="UPDATE ".GROCERIES." SET is_available=$status
		WHERE item='$_POST[item]' AND user_id='$user[id]'
		AND month='$_POST[month]' AND year='$_POST[year]'
		AND week='$_POST[week]'";
	echo $q;
	$_grocery->q($q);
}

exit;