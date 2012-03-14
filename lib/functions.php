<?php
function __autoload($class_name) {
    if(is_file('models/'.strtolower($class_name) . '.php'))
	   require_once 'models/'.strtolower($class_name) . '.php';
}


//offset place
function offset_place($current_offset,$offset_place,$page_limit)
{
	$module_offset= (floor($offset_place / $page_limit)) * $page_limit;
						
	if($current_offset<=$module_offset)
	{
		$calc_offset=$current_offset;
	}
	else
	{
		$calc_offset=$module_offset;
	}
	return $calc_offset;
}

// collects existing POST, GET or SESSION parameters to pass
// them away in links and ajax calls
function collect_params($type, $exclude="")
{
	switch($type)
	{
		case 'GET': $params=$_GET; break;
		case 'POST': $params=$_POST; break;
		case 'SESSION': $params=$_SESSION; break;
	}
	
	$ret_str="";
	$cnt=0;
	foreach($params as $key=>$val)
	{
		if($cnt>0) $ret_str.="&";
		
		if(is_array($exclude))
		{
			if(in_array($key,$exclude)) continue;
		}
		
		$ret_str.=$key."=".urlencode($val);

		$cnt++;
	}
	
	return $ret_str;
}

// singleton access to the DB
function get_db_connection()
{
	static $DB;
	
	if (!is_object($DB)) 
	{
        require_once 'inc/mysql_wrapper.php';
        $DB = new DBClass();

    }
	
	return $DB;
}

// singleton access the context
function get_context()
{
	static $_context;
	
	if (!is_object($_context)) 
	{
		require_once("lib/context.php");
        $_context=new Context();
    }
	
	return $_context;
}
?>