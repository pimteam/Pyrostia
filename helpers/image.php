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
 * Celeroo Frame Image Manipulation Class
 *
 * This class contains functions for image manipulations
 *
 * @package		Celeroo Frame
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Bobby Handzhiev
 * @link		
 */

class Image
{
	
	/**
	 * Creates thumbnail from existing pictures
	 *
	 * @access	public
	 * @param	string	first path
	 * @param	string	file name
	 * @return	string  full path and name of the generated thumbnail
	 */	
	public function make_thumbnail($path,$name)
	{
	   $forcedwidth=150;
	   $forcedheight=120;
	
	   $sourcefile=$path.$name;
	   $destfile=$path."thumb_".$name;
	   
	   //if actual width is smaller than forced, do not stretch
	   $size=@getimagesize($sourcefile);
	   if($size[0]<$forcedwidth) $forcedwidth=$size[0];
	   if($size[1]<$forcedheight) $forcedheitgh=$size[1];
	   
	   //invoke the GD function
	   if(preg_match("/jpg$/i",$sourcefile) || preg_match("/jpeg$/i",$sourcefile))
	   {	   
		   $this->resampimagejpg($forcedwidth, $forcedheight, $sourcefile, $destfile);
	   }
	   elseif(preg_match("/gif$/i",$sourcefile))
	   {
		   $this->resampimagegif($forcedwidth, $forcedheight, $sourcefile, $destfile);
	   }
	   elseif(preg_match("/png$/i",$sourcefile) or preg_match("/ping$/i",$sourcefile))
	   {
		   $this->resampimagepng($forcedwidth, $forcedheight, $sourcefile, $destfile);
	   }
	   else
	   {
		   //do not resize, but only copy
		   @copy($sourcefile,$destfile);
	   }
	   
	   return $destfile;
	}
	
	/**
	 * Resizes a thumbnail proportionally and put it's in a frame
	 *
	 * @access	public
	 * @param	string	thumbnail path
	 * @param	number	max width
	 * @return	number  max height
	 */	
	public function frame($photo,$maxwidth=80,$maxheight=100)
	{
		$size=@getimagesize($photo);
		if(($size[0]*1.5)>$size[1])
		{
			$limit="width=\"$maxwidth\"";
		}
		else
		{
			$limit="height=\"$maxheight\"";
		}
		
		$divwidth=$maxwidth+10;
		$divheight=$maxheight+10;
		
		return "<div style='width:".$divwidth."px;height:".$divheight."px;border:1px solid black;text-align:center;'><img src='$photo' $limit></div>";
	}
	
	/**
	 * Crops an image
	 *
	 * @access	public
	 * @param	string	existing image path
	 * @param	number	crop perentage
	 * @param	number	desired width
	 * @param	number	desired height
	 * @param	string	path of the target file	 
	 */	
	public function crop($image,$crop_percent,$thumb_width,$thumb_height,$target_file)
	{
		list($width, $height) = getimagesize($image); 
		$my_image=imagecreatefromjpeg($this->imgSrc) or die("Error: Cannot find image!"); 
		
		if($width > $height) $large_side = $width; //find larger length
        else $large_side= $height; 
                
	    $crop_width   = $large_side*$crop_percent; 
	    $crop_height  = $large_side*$crop_percent; 
	    
	    //get top and left
	    $x=round(($width-$crop_width)/2);
	    $y = round(($height-$crop_height)/2);
	    
	    //create the cropped image
	    $thumb = imagecreatetruecolor($thumb_width, $thumb_height); 
	    imagecopyresampled($thumb, $my_image, 0, 0,$x, $y, $thumb_width, $thumb_height, crop_width, $crop_height); 
	    imagejpeg($thumb,$target_file);
	}
	
	/**
	 * Reamples jpg image
	 *
	 * @access	private	
	 */	
	private function resampimagejpg($forcedwidth, $forcedheight, $sourcefile, $destfile, $imgcomp=0)
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
	 * Reamples png image
	 *
	 * @access	private	
	 */
	private function resampimagepng($forcedwidth, $forcedheight, $sourcefile, $destfile, $imgcomp=0)
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
	 * Reamples gif image
	 *
	 * @access	private	
	 */
	private function resampimagegif($forcedwidth, $forcedheight, $sourcefile, $destfile, $imgcomp=0)
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
}

// End Image class
?>