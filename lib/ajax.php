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
 * Celeroo Frame Ajax Library
 *
 * @package		Celeroo Frame
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Bobby Handzhiev
 * @link		
 */

// ------------------------------------------------------------------------


/**
 * Parsing request string coming from ajax into variables
 *
 * Puts the variables sent as string in $_POST['request'] into different $_POST variables
 *
 * @access	public
 * @return	true
 */	
function parse_ajax_request()
{
	$request=urldecode($_POST['request']);
	
	//generate array
	$parts=explode("&",$request);
		
	foreach($parts as $part)
	{
		$part=urldecode($part);
		
		$sparts=explode("=",$part);
		
		//array variable?
		if(strstr($sparts[0],"[]"))
		{
			$arrname=str_replace("[]","",$sparts[0]);
			$arrval=$sparts[1];
			
			if(!is_array($_POST["$arrname"])) $_POST["$arrname"]=array();
			array_push($_POST["$arrname"],$arrval);
		}
		else
		{
			//submit normal variable
			$_POST[$sparts[0]]=$sparts[1];
		}
	}
}

// creates a temp ajax template
function ajax_piece($view,$block_id)
{
	$content=file_get_contents($view);
	$code=cut("<!-- start $block_id -->","<!-- end $block_id -->",$content);	
	$__filename = tempnam('/tmp/','Form');    
	file_put_contents($__filename,$code);
    return $__filename;     
}
?>