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
 * Celeroo Frame HTML Library
 *
 * @package		Celeroo Frame
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Bobby Handzhiev, Mahaboob Basha
 * @link		
 */

// ------------------------------------------------------------------------

/**
 * Forces redirect
 *  
 * @access	public
 * @param   string
 * @return	true
 */
function redirect($where)
{
   header("Location: $where");
   exit;
}

/**
 * Generates HTML for pagination
 *  
 * @access	public
 * @param   string
 * @param   number
 * @param   number
 * @param   number
 * @param   number
 * @return	true
 */
// often used function which creates a navigation in the bottom of the page
// when there are too many records to be listed at one
function nav_pages($path,$count,$offset,$limit,$width=200)
{	
   if(!is_numeric($limit)) return true;
	
   if(empty($offset)) $offset=0;
   if(empty($limit)) $limit=10;
   
   //These variable are 3 represents the limit on the left and right sides of the curren page; 9 represents the first pages number
   $pages_limit=3;
   $intial_page=9;
   
   //need previous link?
   if($offset>0)
   {
      $off=$offset-$limit;
      $previous=" <a href=\"".$path."&offset=".$off."\">&lt;&lt; Previous</a> ";
   }
   else $previous="";

   //need next link?
   if(($offset+$limit)<$count)
   {
      $off=$offset+$limit;
      $next=" <a href=\"".$path."&offset=".$off."\" class=\"bluelinks\">Next &gt;&gt;</a> ";
   }
   else $next="";   

   //generate nubmers of pages between
   $pages=$count/$limit;

   if(strstr($pages,"."))
   {
      $pages=substr($pages,0,strpos($pages,"."));
      $pages++;
   }   

   $curpage=$offset/$limit;
   if($pages>1){
   $left_pages=$curpage-0;
   $right_pages=$count-$curpage-1;

  
   //counting no of pages of left and right  
   $left_pages_count=$left_pages-$pages_limit;	
   $right_pages_count=$right_pages-$pages_limit;	
	
   $links="";

   $start_pages_count=2;  //this variable represents staring pages ie., if u r in last page it showing first two page 
      
   $middle_pages_count=$intial_page; //this page represents middle page count
   
   
   $curr_left=$curpage-$pages_limit;   //left pages count  from current page 
   $curr_right=$curpage+$pages_limit+1; //right pages count form current page
      
  //These all if condings are checking wether variable is greater than number of pages or not 
   if($start_pages_count>$pages)
      $start_pages_count=$pages;
   
   if($middle_pages_count>$pages)
      $middle_pages_count=$pages;
    
   if($curr_left>$pages)
      $curr_left=$pages;

   if($curr_right>($pages-2))
       $curr_right=($pages-2);    
  
	// end of if conditions 
	  
  /* selecting first two pages */
   for($i=0;$i<$start_pages_count;$i++)
     {
       $j=$i+1;
       if($i==$curpage) $links.=" <a href=\"".$path."&offset=".($limit*$i)."\" class=\"active\"><b>".$j."</b></a> ";
       else $links.=" <a href=\"".$path."&offset=".($limit*$i)."\" >".$j."</a> ";
     }
   
   /* selecting the  next pages from starting page   */
   if($left_pages_count<$pages_limit || $pages<$intial_page)
   {
     for($i=$start_pages_count;$i<$middle_pages_count;$i++)
     {
       $j=$i+1;
       if($i==$curpage) $links.=" <a href=\"".$path."&offset=".($limit*$i)."\" class=\"active\"><b>".$j."</b></a> ";
       else $links.=" <a href=\"".$path."&offset=".($limit*$i)."\" >".$j."</a> ";
     }
    }
	
	// this if condition is true when number of pages is greater than intial showing page this variable is declared at the  top 
	if($pages>$intial_page){ 
	
	/* selecting middle pages   */
	if($left_pages_count>=$pages_limit  )
	 {
	    $links.=" <a>...</a> ";
	  for($i=$curr_left;$i<$curr_right;$i++)
        {
         $j=$i+1;
         if($i==$curpage) $links.=" <a href=\"".$path."&offset=".($limit*$i)."\" class=\"active\"><b>".$j."</b></a> ";
         else $links.=" <a href=\"".$path."&offset=".($limit*$i)."\" >".$j."</a> ";
       }
	   
	 }	
   
   
   /* selecting last pages  */
	   if($right_pages_count>=$pages_limit)
	      $links.=" <a>...</a> ";
		  
		for($i=$pages-2;$i<$pages;$i++)
         {
	         $j=$i+1;
	         if($i==$curpage) $links.=" <a href=\"".$path."&offset=".($limit*$i)."\" class=\"active\"><b>".$j."</b></a> ";
	         else $links.=" <a href=\"".$path."&offset=".($limit*$i)."\" >".$j."</a> ";
         } 
	 }
   } 
  
   $table= "<p align='center'>";
   $table.= $previous;
   $table.= @$links;
   $table.= $next;
   $table.= "</p>";
   return $table;
}


function nav_pages_rewrite($path,$count,$offset,$limit,$width=200)
{
   
   if(!is_numeric($limit)) return true;
	
   if(empty($offset)) $offset=0;
   if(empty($limit)) $limit=10;
   
   //These variable are 3 represents the limit on the left and right sides of the curren page; 9 represents the first pages number
   $pages_limit=3;
   $intial_page=9;
   
   //need previous link?
   if($offset>0)
   {
      $off=$offset-$limit;
      $previous="<td><a href=\"".$path."offset/".$off."/\">&lt;&lt; Previous</a></td>";
   }
   else $previous="";

   //need next link?
   if(($offset+$limit)<$count)
   {
      $off=$offset+$limit;
      $next="<td><a href=\"".$path."offset/".$off."/\">Next &gt;&gt;</a></td>";
   }
   else $next="";   

   //generate nubmers of pages between
   $pages=$count/$limit;

   if(strstr($pages,"."))
   {
      $pages=substr($pages,0,strpos($pages,"."));
      $pages++;
   }   

   $curpage=$offset/$limit;
   if($pages>1){
   $left_pages=$curpage-0;
   $right_pages=$count-$curpage-1;

  
   //counting no of pages of left and right  
   $left_pages_count=$left_pages-$pages_limit;	
   $right_pages_count=$right_pages-$pages_limit;	
	
   $links="";

   $start_pages_count=2;  //this variable represents staring pages ie., if u r in last page it showing first two page 
      
   $middle_pages_count=$intial_page; //this page represents middle page count
   
   
   $curr_left=$curpage-$pages_limit;   //left pages count  from current page 
   $curr_right=$curpage+$pages_limit+1; //right pages count form current page
      
  //These all if condings are checking wether variable is greater than number of pages or not 
   if($start_pages_count>$pages)
      $start_pages_count=$pages;
   
   if($middle_pages_count>$pages)
      $middle_pages_count=$pages;
    
   if($curr_left>$pages)
      $curr_left=$pages;

   if($curr_right>($pages-2))
       $curr_right=($pages-2);    
  
	// end of if conditions 
	  
  /* selecting first two pages */
   for($i=0;$i<$start_pages_count;$i++)
     {
       $j=$i+1;
       if($i==$curpage) $links.="<td><a href=\"".$path."offset/".($limit*$i)."/\" class=\"active\"><b>".$j."</b></a></td>";
       else $links.="<td><a href=\"".$path."offset/".($limit*$i)."/\" >".$j."</a></td>";
     }
   
   /* selecting the  next pages from starting page   */
   if($left_pages_count<$pages_limit || $pages<$intial_page)
   {
     for($i=$start_pages_count;$i<$middle_pages_count;$i++)
     {
       $j=$i+1;
       if($i==$curpage) $links.="<td><a href=\"".$path."offset/".($limit*$i)."/\" class=\"active\"><b>".$j."</b></a></td>";
       else $links.="<td><a href=\"".$path."offset/".($limit*$i)."/\" >".$j."</a></td>";
     }
    }
	
	// this if condition is true when number of pages is greater than intial showing page this variable is declared at the  top 
	if($pages>$intial_page){ 
	
	/* selecting middle pages   */
	if($left_pages_count>=$pages_limit  )
	 {
	    $links.="<td><a>...</a></td>";
	  for($i=$curr_left;$i<$curr_right;$i++)
        {
         $j=$i+1;
         if($i==$curpage) $links.="<td><a href=\"".$path."offset/".($limit*$i)."/\" class=\"active\"><b>".$j."</b></a></td>";
         else $links.="<td><a href=\"".$path."offset/".($limit*$i)."/\" >".$j."</a></td>";
       }
	   
	 }	
   
   
   /* selecting last pages  */
	   if($right_pages_count>=$pages_limit)
	      $links.="<td><a>...</a></td>";
		  
		for($i=$pages-2;$i<$pages;$i++)
         {
         $j=$i+1;
         if($i==$curpage) $links.="<td><a href=\"".$path."offset/".($limit*$i)."/\" class=\"active\"><b>".$j."</b></a></td>";
         else $links.="<td><a href=\"".$path."offset/".($limit*$i)."/\" >".$j."</a> </td>";
         } 
	 }
   } 
  
   $table.= "<table width=".$width." align=\"center\">";
   $table.= $previous;
   $table.= $links;
   $table.= $next;
   $table.= "</table>";
   return $table;
}


/**
 * Displays Error Message
 *  
 * @access	public
 * @param   string
 * @return	true
 */
function msg_die($message)
{
  $mainmsg="Error!";
  $msg=$message."<br><a href='#' onclick='history.back();return false;'>Go Back</a>";
  $pagename="ERROR";
  $view="views/message.html";
  require_once("views/master.html");
  exit;
}

function json_die($message)
{
    echo json_encode(array("status"=>"error", "message"=>$message));
    exit;
}

function json_success($message)
{
    echo json_encode(array("status"=>"success", "message"=>$message));
    exit;
}

/**
 * Formats a date
 *
 * This function is used for easier unification of the date format thry the entire application  
 *
 * @access	public
 * @param   string
 * @param   boolean
 * @return	string
 */
function dateformat($date,$noyear=0)
{	
   $months=array('','Jan','Feb','Mar','Apr','May','Jun','Jul',
   'Aug','Sep','Oct','Nov','Dec');

   $date=explode(" ",$date);
   $time=@$date[1];
   $date=$date[0];
   
   $date=explode("-",$date);
   
   if($date[1]<10) $date[1]=substr($date[1],1);
   if($noyear)
   {
	  $datestring=$months[$date[1]]." ".$date[2];
   }
   else
   {   
      $datestring=$months[$date[1]]." ".$date[2].", ".$date[0];
   }   
   
   $datestring.=" ".$time;
   return $datestring;
}
/**
 * Formats a date
 *
 * This function is used for easier unification of the date format thry the entire application  
 *
 * @access	public
 * @param   string
 * @param   boolean
 * @return	string
 */
function date_format_customize($date,$format)
{
 $dateTime = new DateTime($date); 
 return date_format($dateTime,$format);
}

/**
 * Creates a dropdown for choosing a date
 *  
 * @access	public
 * @param   string
 * @param   string
 * @return	string
 */
function date_chooser($name,$value="",$startyear=1900)
{
	global $months;
	$endyear=date("Y")+50;
	
	if(empty($value)) $value=date("Y-m-d");
	
	$parts=explode("-",$value);
	
	$day=$parts[2]+0;
	$month=$parts[1]+0;
	$year=$parts[0];
	
	$chooser="";
	
	$chooser.="<select name=\"".$name."month\" class=\"txtfeld\">";
	for($i=1;$i<=12;$i++)
	{
		if($i==$month) $selected='selected';
		else $selected='';
		$chooser.="<option $selected value='$i'>$months[$i]</option>";
	}
	$chooser.="</select> / ";
	
	$chooser.="<select name=\"".$name."day\" class=\"txtfeld\">";
	for($i=1;$i<=31;$i++)
	{
		if($i==$day) $selected='selected';
		else $selected='';
		$chooser.="<option $selected value='$i'>$i</option>";
	}
	$chooser.="</select> / ";
	
	$chooser.="<select name=\"".$name."year\" class=\"txtfeld\">";
	for($i=$startyear;$i<=$endyear;$i++)
	{
		if($i==$year) $selected='selected';
		else $selected='';
		$chooser.="<option $selected value='$i'>$i</option>";
	}
	$chooser.="</select> ";	
	
	return $chooser;
}


/**
 * Creates a dropdown for selecting a value
 *  
 * The function uses single-dimentional array and creates a dropdown 
 *
 * @access	public
 * @param   array
 * @param   array
 * @param   mixed
 * @return	string
 */
function form_sdropdown($values,$displays,$sel='')
{
	foreach($values as $cnt=>$value)
	{
		$value=trim($value);
		
		if(is_array($sel))
		{
			//for multiple selections
			if(in_array($value,$sel)) $selected='selected';
			else $selected='';
		}
		else
		{
			//single selection
			if($sel==$value) $selected='selected';
			else $selected='';
		}
		$options.="<option $selected value=\"".trim($value)."\">".trim($displays[$cnt])."</option>";
	}
	
	return $options;
}


/**
 * Creates a dropdown for selecting a value
 *  
 * The function uses associative array and creates a dropdown
 * where the values of the drodown are the keys of the array
 *
 * @access	public
 * @param   array associative
 * @param   mixed
 * @param   string
 * @param   string
 * @return	string
 */
function form_cdropdown($hashes,$val,$display,$sel='')
{
	foreach($hashes as $cnt=>$hash)
	{
		if(is_array($sel))
		{			
			//multiple selection
			if(in_array($hash["$val"],$sel)) $selected='selected';
			else $selected='';
		}
		else
		{
			if($sel==$hash[$val]) $selected='selected';
			else $selected='';
		}
		$options.="<option $selected value=\"$hash[$val]\">$hash[$display]</option>\n";
	}
	return $options;
}


/**
 * Returns the beginning of a text without breakign words
 *   
 * @access	public
 * @param   string
 * @param   number 
 * @return	string
 */
function resume($text,$limit=7)
{
	$words=@explode(" ",$text);
	
	$words=@array_splice($words,0,$limit);
	
	$retstr=@implode(" ",$words);
	
	return $retstr."... ";
}


/**
 * Returns a group of radio buttons
 *   
 * @access	public
 * @param   string
 * @param   array 
 * @param   array
 * @param   string
 * @param   string
 * @return	string
 */
function radiogroup($name,$vals,$displays="",$selval="",$sep="&nbsp;")
{
	if(!is_array($displays)) $displays=$vals;

	$group="";
	foreach($vals as $cnt=>$val)
	{
		if($val==$selval) $checked="checked";
		else $checked="";
		$group.="<input type=radio name=\"$name\" value=\"$val\" $checked> ".$displays[$cnt]." $sep";
	}

	return $group;
}

//function to get curent URL
function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

//function to limit the text
function limit_text($text,$length)
{
	$strip_text=strip_tags($text);
	if(strlen($strip_text)>$length)
	{
		$limit_text=substr($strip_text,0,$length)."...";
	}
	else
	{
		$limit_text=$strip_text;
	}
	
	return  $limit_text;
}


//function for all popup html table
function show_popup($div_id,$div_width,$div_height,$table_width,$table_content,$div_zindex="999")
{
	$popup_html="<div id=\"".$div_id."\" style=\"position:absolute;display:none; z-index:".$div_zindex.";height:".$div_height."px;width:".$div_width."px;\"><table width=\"".$table_width."\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" bgcolor=\"#FFFFEF\">
  				<tr>
    				<td align=\"right\" valign=\"middle\"><a href=\"javascript:void(0)\" onclick=\"hideElt1('".$div_id."');popupdetails('false');return false;\">X</a></td>
  				</tr>
  				<tr>
    				<td>".$table_content."</td>
  				</tr>
  				<tr>
    				<td>&nbsp;</td>
 				</tr>
				</table></div>";
	return $popup_html;
}

function parse_links($text)
{
	//now prepare links
	$words=preg_split("/\s/",$text);

	foreach($words as $cnt=>$word)
	{
		if(strstr($word,"http://"))
		{ 
			$sword=$word;

			if(strlen($sword)>50) 
			{
				$sword=substr($sword,0,50);
				$sword=$sword."...";
			}

			$word="<a href='$word' target=_blank>$sword</a>";

			$words[$cnt]=$word;
		}
	}

	$text=implode(" ",$words);

	$text=stripslashes($text);

	return nl2br($text);
}


function nav_pages_ajax($params,$count,$offset,$limit)
{
   if(!is_numeric($limit)) return true;
   
   if(empty($offset)) $offset=0;
   if(empty($limit)) $limit=10;
   
   $path=$params["function"]."(";
   foreach ($params as $key => $val)
	 {
	  if($key!="function")
	      $path.="'".$val."',";
	 }
  
   //These variable are 3 represents the limit on the left and right sides of the curren page; 9 represents the first pages number
   $pages_limit=3;
   $intial_page=3;
  
   //need previous link?
   if($offset>0)
   {
      $off=$offset-$limit;
      $previous="<a href=\"#\" onclick=\"".$path."$off); return false;\">&lt;&lt; Previous</a>";
   }
   else $previous="";

   //need next link?
   if(($offset+$limit)<$count)
   {
      $off=$offset+$limit;
      $next="<a href=\"#\" onclick=\"".$path."$off); return false;\">Next &gt;&gt;</a>";
   }
   else $next="";  

   //generate nubmers of pages between
   $pages=$count/$limit;
   if($pages>1){
   if(strstr($pages,"."))
   {
      $pages=substr($pages,0,strpos($pages,"."));
      $pages++;
   }  

   $curpage=$offset/$limit;
  
   $left_pages=$curpage-0;
   $right_pages=$count-$curpage-1;

 
   //counting no of pages of left and right 
   $left_pages_count=$left_pages-$pages_limit;   
   $right_pages_count=$right_pages-$pages_limit;   
   
   $links="";

   $start_pages_count=2;  //this variable represents staring pages ie., if u r in last page it showing first two page
     
   $middle_pages_count=$intial_page; //this page represents middle page count
  
  
   $curr_left=$curpage-$pages_limit;   //left pages count  from current page
   $curr_right=$curpage+$pages_limit+1; //right pages count form current page
     
  //These all if condings are checking wether variable is greater than number of pages or not
   if($start_pages_count>$pages)
      $start_pages_count=$pages;
  
   if($middle_pages_count>$pages)
      $middle_pages_count=$pages;
   
   if($curr_left>$pages)
      $curr_left=$pages;

   if($curr_right>($pages-2))
       $curr_right=($pages-2);   
 
    // end of if conditions
    
     
    
  /* selecting first two pages */
   for($i=0;$i<$start_pages_count;$i++)
     {
       $j=$i+1;
       if($i==$curpage) $links.="<a href=\"#\" onclick=\"".$path.($limit*$i)."); return false;\"><b>".$j."</b></a>";
       else $links.="<a href=\"#\" onclick=\"".$path.($limit*$i)."); return false;\" >".$j."</a>";
     }
  
   /* selecting the  next pages from starting page   */
   #if($left_pages_count<$pages_limit || $pages<$intial_page)
   if($left_pages_count<$pages_limit || $pages<$intial_page)
   {
     for($i=$start_pages_count;$i<$middle_pages_count;$i++)
     {
       $j=$i+1;
       if($i==$curpage) $links.="<a href=\"#\" onclick=\"".$path.($limit*$i)."); return false;\"><b>".$j."</b></a>";
       else $links.="<a href=\"#\" onclick=\"".$path.($limit*$i)."); return false;\" >".$j."</a>";
     }
    }
   
    // this if condition is true when number of pages is greater than intial showing page this variable is declared at the  top
    
    #echo "pages: $pages, initial: $intial_page";
    if($pages>$intial_page){
   #echo "here";
    /* selecting middle pages   */
    if($left_pages_count>=$pages_limit  )
     {
        $links.="<a>...</a>";
      for($i=$curr_left;$i<$curr_right;$i++)
        {
         $j=$i+1;
         if($i==$curpage) $links.="<a href=\"#\" onclick=\"".$path.($limit*$i)."); return false;\"><b>".$j."</b></a>";
         else $links.="<a href=\"#\" onclick=\"".$path.($limit*$i)."); return false;\" >".$j."</a>";
       }
      
     }   
  
  
   /* selecting last pages  */
       if($right_pages_count>=$pages_limit)
          $links.="<a>...</a>";
         
        for($i=$pages-2;$i<$pages;$i++)
         {
         $j=$i+1;
         if($i==$curpage) $links.="<a href=\"#\" onclick=\"".$path.($limit*$i)."); return false;\" ><b>".$j."</b></a>";
         else $links.="<a href=\"#\" onclick=\"".$path.($limit*$i)."); return false;\" >".$j."</a> ";
         }
     }
   }
 
   $table.= "<table><tr><td>";
   $table.= $previous;
   $table.= $links;
   $table.= $next;
   $table.= "</td></tr></table>";
   return $table;
}

function send_post($vars, $url) 
{ 
$strRequestBody = ""; while (list($key, $val) = each($vars)) { if($strRequestBody != "") $strRequestBody.= "&"; $strRequestBody.= $key."=".$val; } $ch = curl_init(); curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); curl_setopt ($ch, CURLOPT_URL, $url); curl_setopt ($ch, CURLOPT_POST, $strRequestBody); curl_setopt ($ch, CURLOPT_POSTFIELDS, $strRequestBody); $return_string = curl_exec ($ch); curl_close ($ch); if ($return_string=="") { $message="Error: Could not post to remote system."; return $message; } return $return_string; } // End function


// outputs appropriate HTML code for displaying 
// an uploaded file depending on the file type
function output_file($filepath)
{
	// check if there are multiple files
	// separated with "|". This is a common method to store multiple files in a field
	// which is also used by celeroo builder
	if(strstr($filepath,"|"))
	{
		$paths=explode("|",$filepath);
		$ret_str="";
		foreach($paths as $path)
		{
			$ret_str.=output_file($path)." ";			
		}
		
		return $ret_str;
	}
	
	if(!@is_file($filepath)) return "";

	$parts=explode(".",$name_filename);
    $extension=$parts[sizeof($parts)-1];
    $extension=strtolower($extension);	
	
	$type=file_type($filepath);
	
	switch($type)
	{
		case 'image':
			// get image size. If less than 200x200 display it with it's size, 
			// otherwise fix to width 200. You can change this default behavior.
			$size=@getimagesize($filepath);
			if($size[0]>200) $size[0]=200;
			
			return '<img src="'.SITE_URL.$filepath.'" align="left" width="'.$size[0].'" hspace="5" vspace="5">';
		break;
		
		case 'audio':
			return '<object>
			<param name="autostart" value="false">
			<param name="src" value="'.SITE_URL.$filepath.'">
			<param name="autoplay" value="true">
			<param name="controller" value="true">
			<embed src="'.SITE_URL.$filepath.'" controller="true" autoplay="true" autostart="True" type="audio/'.$extension.'" />
			</object>';
		break;
		
		case 'video':
			return '<object id="MediaPlayer" classid="clsid:6bf52a52-394a-11d3-b153-00c04f79faa6" type="video/x-ms-wmv" width="352" height="348">
<param name="enableContextMenu" value="false" />
<param name="windowlessvideo" value="true" />
<param name="autostart" value="true" />
<param name="showFrame" value="true" />
<param name="invokeURLs" value="false" />
<param name="stretchtofit" value="false" />
<param name="url" value="'.SITE_URL.$filepath.'" />
<param name="uimode" value="mini" />
<!--[if !IE]> <-->
<object id="MediaPlayer2" type="video/x-ms-wmv" width="352" height="300" data="'.SITE_URL.$filepath.'">
<param name="filename" value="'.SITE_URL.$filepath.'" />
<param name="showcontrols" value="0" />
<param name="animationatstart" value="0" />
<param name="transparentatstart" value="1" />
<param name="showaudiocontrols" value="1" />
<param name="showtracker" value="0" />
<param name="showdisplay" value="0" />
<param name="showstatusbar" value="1" />
</object>
<!--> <![endif]-->
</object><br />
			<a href="'.SITE_URL.$filepath.'">download video</a>'; 
		break;
		
		case 'file':
		default:
			return '<a href="'.SITE_URL.$filepath.'">download file</a>';
		break;
	}
}

// very simple funciton to return the current url
// edit it if you use mod_rewrite
function current_url()
{
	return urlencode($_SERVER['QUERY_STRING']);
}

// extracts filename from file
function filename($filepath)
{
    $parts=explode("/",$filepath);
    $filename=array_pop($parts);
    
    return $filename;
}

// encodes array in json but unsets the numeric keys
function celeroo_json_prepare($var)
{
    foreach($var as $key=>$value)
    {
        if(is_numeric($key)) unset($var[$key]);
    }
    
    return $var;
}
?>