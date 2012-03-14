<?php
# this is the script that actually displays the calendar with meals etc
require("authorize.php");
$_col=new Col();
$_meal=new Meal();
$_calendar=new Calendar();
$_grocery=new Grocery();

# prepare columns
$columns=array(array("name"=>"Breakfast"),array("name"=>"Lunch"),
    array("name"=>"Dinner"),array("name"=>"Snack"));

# add custom columns
$custom_columns=$_col->find(0, array("conditions"=>" user_id='$user[id]'
    AND ((date_from>='{$_calendar->from}' AND date_to<='{$_calendar->to}')
        OR (date_from>='{$_calendar->from}' - INTERVAL 7 DAY 
            AND date_to<='{$_calendar->to}' - INTERVAL 7 DAY
            AND keep_going=1)) ", 
    "page_limit"=>-1));
            
$columns=array_merge($columns,$custom_columns);

if(!empty($_POST['add_to_grocery']))
{   
    foreach($_POST['i'] as $i)
    {        
        #$_grocery->context->set("debug", true);
        $grocery=$_grocery->find(-1, 
                array("conditions"=>" month='$_POST[month]' AND week='$_POST[week]'
                AND year='$_POST[year]' AND user_id='$user[id]' AND meal_id='$_POST[meal_id]'
                AND item LIKE \"".mysql_real_escape_string($_POST['item'][$i])."\" "));          
                # $_grocery->context->set("debug", false);
                
        if(!empty($grocery['id']))
        {   
            #$_grocery->context->set("debug", true);
            $_grocery->edit(array("item"=>$_POST['item'][$i],
            "qty"=>$_POST['qty'][$i], "unit"=>$_POST['measure'][$i]), $grocery['id']);               
            #$_grocery->context->set("debug", false);   
        }
        else
        {
            $_grocery->add(array("item"=>$_POST['item'][$i],
            "qty"=>$_POST['qty'][$i],
            "unit"=>$_POST['measure'][$i],
            "month"=>$_POST['month'], "week"=>$_POST['week'], "year"=>$_POST['year'],
            "user_id"=>$user['id'], "meal_id"=>$_POST['meal_id']));    
        }                
    }    
}

# add/edit meal
$meal=array("id"=>0);
if(!empty($_POST['save_meal']))
{
    if(empty($_POST['id']))
    {
        $_POST['user_id']=$user['id'];
        $mid=$_meal->add($_POST);       
        
        // any groceries with no meal ID now get this one
        $q="UPDATE ".GROCERIES." SET meal_id='$mid' WHERE meal_id=0
        AND user_id='$user[id]' AND year='{$_calendar->year}'
        AND month='{$_calendar->month}' AND week='{$_calendar->cur_week}'";
        $_meal->q($q);
    }
    else
    {     
        $meal=$_meal->find(-1, array("conditions"=>" id='$_POST[id]' AND user_id='$user[id]' "));
        $mid=$meal['id'];
        $_meal->edit($_POST, $mid);
    }    
    
    $meal=$_meal->find($mid);
}

# delete meal
if(!empty($_POST['delete_meal']))
{
    $meal=$_meal->find(-1, array("conditions"=>" id='$_POST[id]' AND user_id='$user[id]' "));    
    $_meal->delete($meal['id']);
}

# now find meals
$meals=$_meal->find(0, array("conditions"=>" user_id='$user[id]' 
    AND date>='{$_calendar->from}' AND date<='{$_calendar->to}' ", "page_limit"=>-1));
    
# match meals to cols and dates to structure the dates array
$dates=array();
foreach($_calendar->dates as $date)
{
    $cols=array();    
    foreach($columns as $col)
    {
        $c=$col;        
        $c['meals']=array();
        foreach($meals as $meal)
        {
            if($meal['date']==$date and $meal['col']==$c['name'])
            {
                $c['meals'][]=$meal;
            }
        }        
        $cols[]=$c;
    }
    $dates[]=array("date"=>dateformat($date, true), "mysqldate"=>$date, "columns"=>$cols);
}    

# select grocery items for the given week
$groceries=$_grocery->find(0, array("page_limit"=>-1, "from_tables"=>GROCERIES." tG
	LEFT JOIN ".GROCERIES." tGG ON tG.id=tGG.id AND tGG.is_available=1",
	"fields"=>"SUM(tG.qty) as total_qty, tG.item as item, tG.unit as unit, 
	tG.month as month,  tG.meal_id as meal_id, tG.year as year, tG.week as week, 
	tG.user_id as user_id, tG.qty as qty, SUM(tGG.qty) as sum_available",
    "conditions"=>" tG.user_id='$user[id]' AND tG.year='{$_calendar->year}'
    AND tG.month='{$_calendar->month}' AND tG.week='{$_calendar->cur_week}'",
    "groupby"=>"tG.item", "orderby"=>"tG.id"));
    
// make zero qtys as empty string because I can't hide them in the js template
foreach($groceries as $cnt=>$grocery)
{
    if($grocery['qty']==0)
    {
        $groceries[$cnt]['qty']="";
    }   

	if($grocery['total_qty']<=$grocery['sum_available']) $groceries[$cnt]['is_available']=1;
	else $groceries[$cnt]['is_available']=0;
}    

# now output dates as json
$_calendar->dates=$dates; // assign the dates to the main object
$_calendar->columns=$columns; // assign columns
$_calendar->groceries=$groceries;

echo json_encode(array("calendar"=>$_calendar, "meal"=>$meal));
exit;
?>