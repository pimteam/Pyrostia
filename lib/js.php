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
 * Celeroo Frame Javascript Library
 *
 * @package		Celeroo Frame
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Bobby Handzhiev
 * @link		
 */

// ------------------------------------------------------------------------

/**
 * Forces redirect with javascrit
 *  
 * @access	public
 * @param   string
 * @return	true
 */
function jsredirect($where)
{
   echo "<script language='javascript' type='text/javascript'>
   self.location.href='".$where."';
   </script>";
}

/**
 * Outputs a javascript alert
 *  
 * @access	public
 * @param   string
 * @param   boolean
 * @return	true
 */
function alert($what,$output=false)
{
   $GLOBALS['jsalert']="<script language=javascript type=text/javascript>
   alert('$what');
   </script>";
   
   if($output)
   {
	   echo $GLOBALS['jsalert'];
   }
}

/**
 * Creates a javascript code for typical form validation
 *  
 * @access	public
 * @param   array
 * @param   boolean
 * @return	string
 */
function jsvalidate($fields,$havepass=0)
{
	foreach($fields as $field)
	{
		$js.="var $field=frm.$field.value;\n";

		if(strstr($field,"email"))
		{
			$js.="if(!checkMail($field))
			{
				frm.$field.focus();
				return false;
			}\n\n";
		}
		else
		{
			$js.="if($field==\"\")
			{
				alert(\"You have missed a required field.\");
				frm.$field.focus();
				return false;
			}\n\n";
		}
	}

	if($havepass)
	{
		$js.="var pass=frm.pass.value;
		var repass=frm.repass.value;

		if(pass=='')
		{
			alert(\"Please enter valid password.\");
			frm.pass.focus();
			return false;
		}

		if(pass!=repass)
		{
			alert(\"Password confirmation failed.\");
			frm.repass.focus();
			return false;
		}\n\n	";
	}

	return $js;
}
?>