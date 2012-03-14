<?php
/**
 * Celeroo Frame
 *
 * An open source rapid development framework for PHP 4.3.2 or newer
 *
 * @package		Celeroo Frame
 * @author		Celeroo, www.celeroo.com
 * @copyright	Copyright (c) 2008, Celeroo.
 * @license		
 * @link		
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Celeroo Frame DB Library
 *
 * @package		Celeroo Frame
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Bobby Handzhiev
 * @link		
 */

// ------------------------------------------------------------------------


/**
 * Gets a setting value from the settings table in the DB
 *  
 * @access	public
 * @param   string
 * @return	string
 */	
function get_setting($name)
{
	global $DB, $T_SETTINGS;
	
	$q="SELECT value FROM $T_SETTINGS
	WHERE name=\"$name\"";
	$value=$DB->oq($q);
	
	return $value;
}

/**
 * Dumps array of values in a DB table
 *
 * An easy way to do an insert query. All fields that exist 
 * both in the table and in the passed array will be included in the query.
 *
 * @access	public
 * @param   string
 * @param   array
 * @return	true
 */	
function dumpto_table($table,$vars)
{
	global $DB;

	//select fields from table
	$q="SHOW FIELDS FROM $table";
	$fields=$DB->aq($q);

	$q="INSERT INTO `$table` (";

	foreach($fields as $field)
	{
		if(isset($vars[$field['Field']]))
		{
			$q.="`".$field['Field']."`,";
		}
	}

	$q=substr($q,0,strlen($q)-1);
	$q.=") VALUES (";

	foreach($fields as $field)
	{
		if(isset($vars[$field['Field']]))
		{
			if(is_array($vars[$field['Field']]))
			{
				$valstr=implode("|",$vars[$field['Field']]);
				if (!ini_get('magic_quotes_gpc')) $valstr=mysql_real_escape_string($valstr);
				$q.="\"$valstr\",";
			}
			else
			{	
				if (!ini_get('magic_quotes_gpc'))
				{
					$vars[$field['Field']]=mysql_real_escape_string($vars[$field['Field']]);
				}				
				$q.="\"".$vars[$field['Field']]."\",";
			}
		}
	}
	$q=substr($q,0,strlen($q)-1);
	$q.=")";
	$DB->q($q);
	
	$lid=$DB->lid();
	
	return $lid;
}

/**
 * Updates a DB table with the values in a passed array
 *
 * An easy way to do an update query. All fields that exist 
 * both in the table and in the passed array will be included in the query.
 *
 * @access	public
 * @param   string
 * @param   array
 * @param   string
 * @param   mixed
 * @return	true
 */	
function update_table($table,$vars,$fld,$vle)
{
	global $DB;
	
	if (!ini_get('magic_quotes_gpc')) $vle=mysql_real_escape_string($vle);
	
	//select fields from table
	$q="SHOW FIELDS FROM $table";
	$fields=$DB->aq($q);

	$q="UPDATE `$table` SET ";

	foreach($fields as $field)
	{
		if(isset($vars[$field['Field']]))
		{
			if(is_array($vars[$field['Field']]))
			{
				$valstr=implode("|",$vars[$field['Field']]);
				if (!ini_get('magic_quotes_gpc')) $valstr=mysql_real_escape_string($valstr);
				$q.="`$field[Field]`=\"$valstr\", ";
			}
			else
			{
				if (!ini_get('magic_quotes_gpc'))
				{
					$vars[$field['Field']]=mysql_real_escape_string($vars[$field['Field']]);
				}
				$q.="`$field[Field]`=\"".$vars[$field['Field']]."\", ";				
			}
		}
	}

	$q=substr($q,0,strlen($q)-2);
	
	$q.=" WHERE `$fld`='$vle' ";    
	$DB->q($q);
}

?>