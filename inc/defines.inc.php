<?php
	//prefix for tables in database
	$prefix="pyr_";
	
	//names of the tables
	$T_SETTINGS=$prefix."settings";
	define("SETTINGS",$T_SETTINGS);
	$T_USERS=$prefix."users";
	define("USERS",$T_USERS);
	$T_COLS=$prefix."cols";
    define("COLS",$T_COLS);
    $T_GROCERIES=$prefix."groceries";
    define("GROCERIES",$T_GROCERIES);                    		
    $T_MEALS=$prefix."meals";
    define("MEALS",$T_MEALS);
		
	//set error level
	error_reporting(E_ALL); 		
	
	//months array for date format
	$months=array('','January','February','March','April','May','June','July','August',
	'September','October','November','December');
    
   $currencies=array('USD'=>'$', "EUR"=>"&euro;", "GBP"=>"&pound;", "JPY"=>"&yen;", "AUD"=>"AUD",
   "CAD"=>"CAD", "CHF"=>"CHF", "CZK"=>"CZK", "DKK"=>"DKK", "HKD"=>"HKD", "HUF"=>"HUF",
   "ILS"=>"ILS", "MXN"=>"MXN", "NOK"=>"NOK", "NZD"=>"NZD", "PLN"=>"PLN", "SEK"=>"SEK",
   "SGD"=>"SGD");
	
	$offset=isset($_GET['offset'])?$_GET['offset']:0;
	
	if(is_object($DB))
	{	
		$q="SELECT * FROM $T_SETTINGS ORDER BY id";		
		$settings=$DB->aq($q);
				
		foreach($settings as $setting)
		{
			define(strtoupper($setting['name']),$setting['value']);
		}
	}
    
    define("CRYPTKEY","XYZXYZ");
?>