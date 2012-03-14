<?php
class User extends Basic
{
    function add($vars)
    {
        // unique mail
        $exists=$this->find(array("field"=>"email", "value"=>$vars['email']));
        
        if(!empty($exists['id']))
        {
            throw new Exception("User already exists");
        }
        
        $vars['pass']=md5($vars['pass']);
        $vars['regdate']=date("Y-m-d");
        return parent::add($vars);
    }
    
    function edit($vars, $id)
    {
        if(!empty($vars['pass']))
        {
            $vars['pass']=md5($vars['pass']);
        }
    }
}
?>