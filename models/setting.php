<?php
class Setting extends Basic
{
    // sets a setting or array of settings
	function set($settings)
	{
		if(!is_array($settings)) $settings=array($settings);
		
		foreach($settings as $setting)
		{
			if(empty($this->user_id)) $exists=$this->find(array("field"=>"name","value"=>$setting));
			else
			{
				$exists=$this->find(-1, array("conditions"=>" name='$setting' 
				AND user_id='{$this->user_id}' "));
			}
			
			if(!$exists['id'])
			{
				$this->add(array("name"=> $setting, "value"=> $_POST[$setting], 
				"defkey" => strtoupper($setting), "user_id"=>@$this->user_id));	
			}
			else
			{
				$this->edit(array("name"=>$setting, "value"=>$_POST[$setting]),$exists['id']);	
			}
			
		}
	}	
	
	// quick funciton to return value by name
	function get($name)
	{
		if(!$this->user_id) $setting=$this->find(array("field"=>"name","value"=>$name));
		else
		{
			$setting=$this->find(-1, array("conditions"=>" name='$name' 
				AND user_id='{@$this->user_id}' "));
		}
		
		return $setting['value'];
	}
}
?>