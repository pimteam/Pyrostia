<?php
// doing various ajax checks
switch(@$_GET['do'])
{
    case 'unique_email':
        $_user=new User();
        $exists=$_user->find(array("field"=>"email", "value"=>$_GET['email']));
        
        if(empty($exists['id'])) echo "True";
        else echo "False";
    break;
}
exit;
?>