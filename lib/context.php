<?php
/* This object will contain the current context when the application needs to behave differently
depending on where we are */
class Context
{
	protected $params;
	
	function __construct()
	{
		$this->params=array();
		$this->params['env']="USER";
		$this->params['debug']=false;
		$this->params['cache']=false;
	}
	
	function set($param,$value)
	{
		$this->params[$param]=$value;
	}
	
	function get($param)
	{
		if(!isset($this->params[$param])) return false;
		
		return $this->params[$param];
	}
}
?>