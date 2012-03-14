<?php
# Some meal related functions
require("authorize.php");
$_meal=new Meal();

switch($_POST['do'])
{
    case 'get_meal':
        if(empty($_POST['id']))
        {
            echo json_encode(array("id"=>0, "name"=>""));
            exit;
        }
        
        $meal=$_meal->find(-1, array("conditions"=>" id='$_POST[id]' AND user_id='$user[id]' "));        
        echo json_encode($meal);
    break;
    
    case 'parse_ingredients':
        $ingreds=explode("\n",$_POST['ingredients']);        
        $measures=array("gr","grams","lb","lbs","ounces","kg","pint","tbsp","t","tbs","oz",
            "pt","qt","gal","dash","pinch","cup","gallon","bushel");
            
        $ings=array();
        foreach($ingreds as $i=>$ingred)    
        {
            $ingred=trim($ingred);
            if(empty($ingred)) continue;
            
            $words=preg_split("/\s/",$ingred);
            
            if(sizeof($words)==1)
            {
                $ings[]=array("qty"=>"","item"=>$words[0], "measure"=>"", "i"=>$i);
            }
            
            if(sizeof($words)==2)
            {
                $ings[]=array("qty"=>$words[0], "measure"=>"", "item"=>$words[1], "i"=>$i);    
            }
            
            if(sizeof($words)>=3)
            {
                // qty is always the first word
                $qty=$words[0];
                
                // check if second word is in measures, if it is, then it's measure
                // then all words after the second are the item
                if(in_array($words[1], $measures))
                {
                    $measure=$words[1];
                    $words=array_slice($words,2);
                }
                else
                {
                    // if no measure is found all words after the 1st are the item    
                    $measure="";
                    $words=array_slice($words,1);
                }                
                
                $item=implode(" ",$words);
                
                $ings[]=array("qty"=>$qty, "item"=>$item, "measure"=>$measure, "i"=>$i);
            }
        }        
        
        echo json_encode($ings);
    break;
}
exit;
?>