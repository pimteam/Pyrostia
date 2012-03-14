<?php
function send_mail($from,$to,$subject,$message,$ctype='text/html')
{   
   $mail_mime = "MIME-Version: 1.0\n";
   $mail_mime .= "Content-Type: $ctype; charset=UTF-8\n";
   $mail_mime .= "Message-ID: <".md5($to).time()."@".$_SERVER['HTTP_HOST'].">\n";
   $mail_mime .= "Content-Transfer-Encoding: 8bit\r\n";  
   $encoded_subject="=?UTF-8?B?".base64_encode($subject)."?=\r\n";
   $texte =$message;
   
   $texte=stripcslashes($texte);
   
   #echo $to.' '.$subject.' '.$texte;
   
   // extract from address
   if(strstr($from, " "))
   {
   	 $parts=explode(" ",$from);
   	 $from_email=$parts[1];
	 $from_email=str_replace(">","",$from_email);
	 $from_email=str_replace("<","",$from_email);
   }
   else $from_email=$from;
         
   if(!mail($to, $encoded_subject, $texte,"Reply-to: $from\nFrom: $from\n".$mail_mime)) 
   {
   		echo ("Cannot send mail.");
   }   					
}

/**
 * Easy file upload function - for more file operations check the file utility helper
 *   
 */
function upload_file($name, $dir="files/",$newname="",$filetypes="", $extra_options="")
{	
   if(!empty($_FILES["$name"]))
   {
	  $name_filename=$_FILES["$name"]['name'];

      //getting extension of the file
      $parts=explode(".",$name_filename);
      $extension=$parts[sizeof($parts)-1];
      $extension=strtolower($extension);
		
	  if(!empty($filetypes))
  	  {	
	      if(!in_array($extension,$filetypes))
	      {
		      throw new Exception($name_filename.": This file type is not allowed.");
	      }
      }
	  
	  if(is_array($extra_options))
	  {
	  	// file size limit	
	  	if(!empty($extra_options["size_limit"]) and $extra_options["size_limit"]>0)
		{
			$size=@filesize($_FILES["$name"]["tmp_name"]);
			$size_mb=round($size/(1024*1024),2);
			
			if($size_mb>$extra_options["size_limit"])
			{
				throw new Exception("The file size exceeds the allowed $extra_options[size_limit]MB size.");
			}
		}
	  }
      
      $extension=".$extension";      

      if(empty($newname))
	  {
	     $newname=md5(time().$name_filename).$extension;
	  }
	  
      if(!copy($_FILES[$name]['tmp_name'],$dir.$newname))
      {
           throw new Exception("Can't copy file");
      }

   }
   return $newname;
}


/**
 * Forces file for download
 *  
 * @access	public
 * @param   string
 * @param   string
 * @return	true
 */
function Output($buffer,$name='')
{
	//Output to some destination
	global $HTTP_SERVER_VARS;
	
	//Normalize parameters
	if($name=='')
	{
		$name='file.txt';		
	}
	//Download file
	if(isset($HTTP_SERVER_VARS['HTTP_USER_AGENT']) and strpos($HTTP_SERVER_VARS['HTTP_USER_AGENT'],'MSIE'))
		Header('Content-Type: application/force-download');
	else
		Header('Content-Type: application/octet-stream');
		if(headers_sent())
				msg_die('Some data has already been output to browser, can\'t send the file.');
			Header('Content-Length: '.strlen($buffer));
			Header('Content-disposition: attachment; filename='.$name);
			echo $buffer;
	return '';
}

/**
 * Cuts a string by given patterns
 *  
 * @access	public
 * @param   string
 * @param   string
 * @param   string
 * @return	string
 */
function cut($start,$end,$word)
{	
	$word=substr($word,strpos($word,$start)+strlen($start));
	
	$word=substr($word,0,strpos($word,$end));
	return $word;
}


/**
 * Helper function for resampling image
 *  
 * @access	public
 * @param   number
 * @param   number
 * @param   string
 * @param   string
 * @param   number
 * @return	string
 */
function resampimagejpg($forcedwidth, $forcedheight, $sourcefile, $destfile, $imgcomp=0)
{
   $g_imgcomp=100-$imgcomp;
   $g_srcfile=$sourcefile;
   $g_dstfile=$destfile;
   $g_fw=$forcedwidth;
   $g_fh=$forcedheight;

   if(file_exists($g_srcfile))
   {
     $g_is=getimagesize($g_srcfile);
     if(($g_is[0]-$g_fw)>=($g_is[1]-$g_fh))
     {
        $g_iw=$g_fw;
        $g_ih=($g_fw/$g_is[0])*$g_is[1];
     }
     else
     {
        $g_ih=$g_fh;
        $g_iw=($g_ih/$g_is[1])*$g_is[0]; 
     }
     $img_src=imagecreatefromjpeg($g_srcfile);
     $img_dst=imagecreatetruecolor($g_iw,$g_ih);
     imagecopyresampled($img_dst, $img_src, 0, 0, 0, 0, $g_iw, $g_ih, $g_is[0], $g_is[1]);
     imagejpeg($img_dst, $g_dstfile, $g_imgcomp);
     imagedestroy($img_dst);
   return true;
   }
   else
   return false;
}

/**
 * Helper function for resampling image
 *  
 * @access	public
 * @param   number
 * @param   number
 * @param   string
 * @param   string
 * @param   number
 * @return	string
 */
function resampimagepng($forcedwidth, $forcedheight, $sourcefile, $destfile, $imgcomp=0)
{
   $g_imgcomp=100-$imgcomp;
   $g_srcfile=$sourcefile;
   $g_dstfile=$destfile;
   $g_fw=$forcedwidth;
   $g_fh=$forcedheight;

   if(file_exists($g_srcfile))
   {
     $g_is=getimagesize($g_srcfile);
     if(($g_is[0]-$g_fw)>=($g_is[1]-$g_fh))
     {
        $g_iw=$g_fw;
        $g_ih=($g_fw/$g_is[0])*$g_is[1];
     }
     else
     {
        $g_ih=$g_fh;
        $g_iw=($g_ih/$g_is[1])*$g_is[0]; 
     }
     $img_src=imagecreatefrompng($g_srcfile);
     $img_dst=imagecreatetruecolor($g_iw,$g_ih);
     imagecopyresampled($img_dst, $img_src, 0, 0, 0, 0, $g_iw, $g_ih, $g_is[0], $g_is[1]);
     imagepng($img_dst, $g_dstfile, $g_imgcomp);
     imagedestroy($img_dst);
   return true;
   }
   else
   return false;
}

/**
 * Helper function for resampling image
 *  
 * @access	public
 * @param   number
 * @param   number
 * @param   string
 * @param   string
 * @param   number
 * @return	string
 */
function resampimagegif($forcedwidth, $forcedheight, $sourcefile, $destfile, $imgcomp=0)
{
   $g_imgcomp=100-$imgcomp;
   $g_srcfile=$sourcefile;
   $g_dstfile=$destfile;
   $g_fw=$forcedwidth;
   $g_fh=$forcedheight;

   if(file_exists($g_srcfile))
   {
     $g_is=getimagesize($g_srcfile);
     if(($g_is[0]-$g_fw)>=($g_is[1]-$g_fh))
     {
        $g_iw=$g_fw;
        $g_ih=($g_fw/$g_is[0])*$g_is[1];
     }
     else
     {
        $g_ih=$g_fh;
        $g_iw=($g_ih/$g_is[1])*$g_is[0]; 
     }
     $img_src=imagecreatefromgif($g_srcfile);
     $img_dst=imagecreate($g_iw,$g_ih);
     imagecopyresampled($img_dst, $img_src, 0, 0, 0, 0, $g_iw, $g_ih, $g_is[0], $g_is[1]);
     imagegif($img_dst, $g_dstfile, $g_imgcomp);
     imagedestroy($img_dst);
   return true;
   }
   else
   return false;
}

/**
 * Thumbnail generation function. Deprecated, for more options see the Image helper class
 *  
 * @access	public
 * @param   string
 * @param   string
 * @return	string
 */
function make_thumbnail($path,$name,$width,$height)
{
   $forcedwidth=$width;
   $forcedheight=$height;

   $sourcefile=$path.$name;
   $destfile=$path.$name;
   
   //if actual width is smaller than forced, do not stretch
   $size=@getimagesize($sourcefile);
   if($size[0]<$forcedwidth) $forcedwidth=$size[0];
   if($size[1]<$forcedheight) $forcedheight=$size[1];
   
   //invoke the GD function
   if(preg_match("/jpg$/i",$sourcefile) || preg_match("/jpeg$/i",$sourcefile))
   {	   
	   resampimagejpg($forcedwidth, $forcedheight, $sourcefile, $destfile);
   }
   elseif(preg_match("/gif$/i",$sourcefile))
   {
	   resampimagegif($forcedwidth, $forcedheight, $sourcefile, $destfile);
   }
   elseif(preg_match("/png$/i",$sourcefile) or preg_match("/ping$/i",$sourcefile))
   {
	   resampimagepng($forcedwidth, $forcedheight, $sourcefile, $destfile);
   }
   else
   {
	   //do not resize, but only copy
	   copy($sourcefile,$destfile);
   }
   
   return $destfile;
}


/**
 * Writes a file. For more options check the File utility class
 *  
 * @access	public
 * @param   string
 * @param   string
 * @return	true
 */
function write($path,$content)
{
	$fp=fopen($path,"wb");
	fwrite($fp,$content);
	fclose($fp);
} 

/**
* Handles URL rewritten arguments
*
* @access	public
* @return 	true
*/
function celeroo_routes()
{
	if(!empty($_GET["celeroo_request"]))
	{
		$parts=explode("/",$_GET["celeroo_request"]);
		
		foreach($parts as $cnt=>$part)
		{
			if($cnt%2==0)
			{
				$_GET[$part]=$parts[$cnt+1];
			}
		}
		
		unset($_GET["celeroo_request"]);
	}
}

/**
* Defines the file type based on its extension
*
* @acess 	public
* @return 	string
*/
function file_type($filepath)
{
	$parts=explode(".",$filepath);
    $extension=$parts[sizeof($parts)-1];
    $extension=strtolower($extension);	
    
    switch($extension)
    {
	    case 'gif': case 'jpg': case 'png': case 'bmp': case 'jpeg': $type="image"; break;
	    
	    case 'mp3': case 'wav': $type="audio"; break;
	    
	    case 'avi': case 'mov': case 'mpeg': case 'wmv': $type="video"; break;
	    
	    default: $type="file"; break;
    }
    
    return $type;
}

// converts CSV file to array
function csv_to_array($filename='', $delimiter=',')
{
    if(!file_exists($filename) || !is_readable($filename)) return FALSE;
    
    $data = array();
    if (($handle = fopen($filename, 'rb')) !== FALSE)
    {
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
        {
            $data[]=$row;
        }
        fclose($handle);
    }
    return $data;
}

// converts data to CSV string
function sputcsv($row, $delimiter = ',', $enclosure = '"')
{
    static $fp = false;
    
    if ($fp === false)
    {
        $fp = fopen('php://temp', 'r+'); // see http://php.net/manual/en/wrappers.php.php - yes there are 2 '.php's on the end.
        // NB: anything you read/write to/from 'php://temp' is specific to this filehandle
    }
    else
    {
        rewind($fp);
    }
    
    if (fputcsv($fp, $row, $delimiter, $enclosure) === false)
    {
        return false;
    }
    
    rewind($fp);
    $csv = fgets($fp);
    
    return $csv;
}
?>