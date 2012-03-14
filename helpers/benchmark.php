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
 * PHP Systems Benchmark Class
 *
 * This class contains functions that enable benchmarking code performance
 *
 * @package		Celeroo Frame
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Bobby Handzhiev
 * @link		
 */

class Benchmark
{
	var $anchors = array();
	
	/**
	 * Constructor
	 *
	 * Sets the start marker as a class variable
	 *
	 * @access   public	
	 */
	function __construct()
	{
		$this->start=microtime();
	}
	
	/**
	 * Set an achor
	 *
	 * @access	public
	 * @param	string	the the anchor name	 
	 */	
	public function anchor($name)
	{
		$this->anchors[$name] = microtime();		
	}
	
	/**
	 * Show time elapsed between two anchors
	 *
	 * @access	public
	 * @param	string	first anchor name
	 * @param	string	second anchor name
	 * @return	string  elapsed time in microseconds
	 */	
	public function show($start, $end)
	{	
		list($sm, $ss) = explode(' ', $this->anchors[$start]);
		list($em, $es) = explode(' ', $this->anchors[$end]);

		return number_format(($em + $es) - ($sm + $ss), 4);
	}
	
	/**
	 * Show list of all ahcnor and elapsed time from the start to each of them
	 *
	 * @access	public
	 * @return	string  list of anchors and elapsed time
	 */	
	public function show_all()
	{
		sort($this->anchors);
		
		list($sm, $ss) = explode(' ', $this->start);
		
		foreach($this->anchors as $key=>$anchor)
		{
			list($em, $es) = explode(' ', $anchor);
			$diff=number_format(($em + $es) - ($sm + $ss), 4);
			
			$text.=" Anchor $key: $diff<br> ";
		}
		
		return $text;
	}
}

// End Benchmark class
?>