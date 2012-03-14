<?php
class Calendar
{
    function __construct()    
    {
        # find days in the current week
        $year=empty($_POST['year'])?date("Y"):$_POST['year'];
        $month=empty($_POST['month'])?date("m"):$_POST['month'];
        $selected=getdate(strtotime($month."/1/".$year));
        $wday=$selected['wday'];
        
        // next and previous
        $next_year=$prev_year=$year; // defaults to the same, it isn't really next year, but next month
        $next_month=$month+1;
        $prev_month=$month-1;
        
        if($month==12)
        {
            $next_month=1;
            $next_year=$year+1;                                    
        }
        
        if($month==1)
        {
            $prev_month=12;
            $prev_year=$year-1;
        }

        # how many days in month
        $numdays=date("t", strtotime($year . "-" . $month . "-01")); 

        // define current week (it's either passed in get or is current)   
        $currentWeek = ceil((date("d") - date("w") - 1) / 7) + 1;
        $cur_week=empty($_POST['week'])?$currentWeek:$_POST['week'];

        // number of days from the prev month in the first week
        $first_week_extra=$wday-1;
           
        // number of days at the end
        $selected=getdate(strtotime($month."/".$numdays."/".$year));
        $lday=$selected['wday'];
        $last_week_extra=$lday?7-$lday:0;
           
        $total_days=$numdays+$first_week_extra+$last_week_extra;   
        $total_weeks=$total_days/7;
           
        // let's figure out the dates in the selected week
        $dates=array();
           
        // first week 
        if($cur_week==1) 
        {
           if($first_week_extra)  
           {
              $first_day=mktime(0,0,0,$month*1,1,$year*1);
              $dates[1]=date("Y-m-d",$first_day-$first_week_extra*24*3600);
           }
           else $dates[1]=date("Y-m-d",mktime(0,0,0,$month,1,$year));
        }   
        else
        {
           // other week
           $monday_date=($cur_week-2)*7+(7-$first_week_extra)+1;      
           $dates[1]=date("Y-m-d",mktime(0,0,0,$month,$monday_date,$year)); 
        }
           
        $dateparts=explode("-",$dates[1]);
              
        // next 6 days
        for($i=1;$i<=6;$i++)
        {
           $dates[$i+1]=date("Y-m-d", 
              mktime(0,0,0,$dateparts[1],$dateparts[2],$dateparts[0]) + 24*3600*$i);
        }
        
        $this->dates=$dates;
        $this->from=$dates[1];
        $this->to=$dates[7];
        $this->year=$year;
        $this->month=$month;        
        $this->monthname=date("F",strtotime(sprintf("%2d",$month)."/01/".date("Y")));
        $this->next_month=$next_month;
        $this->next_year=$next_year;
        $this->prev_month=$prev_month;
        $this->prev_year=$prev_year;
        $this->weeks=array();
        $this->cur_week=$cur_week;
        for($i=1;$i<=$total_weeks;$i++) $this->weeks[]=array("i"=>$i, "active"=>($cur_week==$i));
    }
}
?>