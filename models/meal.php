<?php
class Meal extends Basic
{
    // 
    
    function delete($id)
    {
        // only I can delete a meal
        $meal=$this->find($id);
        
        if($meal['user_id']!=$_SESSION['l_user'])
        {
            throw new Exception("Can't delete meal that's not yours");
        }
    
        // delete groceries
        $q="DELETE FROM ".GROCERIES." WHERE meal_id='$id'";
        $this->q($q);
        
        return parent::delete($id);
    }
}
?>