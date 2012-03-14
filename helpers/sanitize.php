<?php
/**
 * Celeroo Frame
 *
 * An open source rapid development framework for PHP 4.3.2 or newer
 *
 * @package		Celeroo Frame
 * @author		Celeroo , www.celeroo.com
 * @copyright	Copyright (c) 2008, celeroo.com	
 * @link		http://www.celeroo.com/frame/frame.html
 * @since		Version 1.0
 */

// ------------------------------------------------------------------------

/**
 * Celeroo Frame Sanitize Class
 * 
 * Performs basic data sanitization
 *
 * @package		Celeroo Frame
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Bobby Handzhiev
 * @link		http://www.celeroo.com/frame/frame.html
 */

// ------------------------------------------------------------------------


class SanitizeHelper
{
	function __construct()
	{
		// do nothing
	}
	
	/**
	 * Prepares HTML code for safe display or DB input
	 *
	 * @param string $string
	 * @param int $level 
	 * @return string
	 * @access public
	 */
	public static function html_safe(&$string,$level=0)
	{
		switch($level)
		{
			case 1:
				$string=strip_tags($string);
			break;
		
			case 0:
			default:
				// $string=htmlentities($string);
			break;
		}
		
		return TRUE;
	}	
	
	/**
	 * Prepares string for SQL query
	 *
	 * @param string $string
	 * @return string
	 * @access public
	 */
	public static function sql_safe(&$string)
	{
		if (!ini_get('magic_quotes_gpc')) {
			$string = addslashes($string);
		}
		
		return TRUE;
	}
	
	// paranoid sanitization - only let the alphanumeric string
	public static function paranoid(&$string)
	{
	  $string = preg_replace("/[^a-zA-Z0-9]/", "", $string);
	}
 }
// End SanitizeHelper class
?>