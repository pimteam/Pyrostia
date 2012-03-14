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
 * PHP Systems Encryption Class
 *
 * This class contains functions that enable benchmarking code performance
 *
 * @package		Celeroo Frame
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Bobby Handzhiev
 * @link		
 */
 
class Encrypt 
{
	/**
	 * Constructor
	 *
	 * sets the key
	 *
	 * @access   public	
	 */
	function __construct($key)
	{
		$this->ckey=$key;
	}
  	
	/**
	 * Encodes a string
	 *
	 * @access	public
	 * @param	string	string to encode	 
	 * @return	string  encoded string
	 */	
	public static function encode($string)
	{
		$encr[] = '';
		$piece[] = '';
		
		$encrlength = strlen($this->key);
		$strlen = strlen($string);
		
		for ($i = 0; $i < 256; $i++)
		{
			$encr[$i] = ord($this->key[$i % $encrlength]);
			$piece[$i] = $i;
		}
		
		for ($j = $i = 0; $i < 256; $i++)
		{
			$j = ($j + $piece[$i] + $encr[$i]) % 256;
			$piece[$i] ^= $piece[$j];
			$piece[$j] ^= $piece[$i];
			$piece[$i] ^= $piece[$j];
		}
		
		for ($a = $j = $i = 0; $i < $strlen; $i++)
		{
			$a = ($a + 1) % 256;
			$j = ($j + $piece[$a]) % 256;
			
			$piece[$a] ^= $piece[$j];
			$piece[$j] ^= $piece[$a];
			$piece[$a] ^= $piece[$j];
			
			$k = $piece[(($piece[$a] + $piece[$j]) % 256)];
			$retstr .= chr(ord($data[$i]) ^ $k);
		
		}
		
	    return $retstr;
	}
  	
	/**
	 * Decodes a string encoded with this class
	 *
	 * @access	public
	 * @param	string	string to decode	 
	 * @return	string  decoded string
	 */	
	public static function decode($string)
	{
		return $this->encrypt($this->key, $string);
	}
}

// END Encrypt class
?>