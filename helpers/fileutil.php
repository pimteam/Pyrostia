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
 * Celeroo Frame File Utility Class
 *
 * This class contains functions that help working with files and directories
 *
 * @package		Celeroo Frame
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Bobby Handzhiev
 * @link		
 */
class FileUtil
{	
	/**
	 * Upload a file in existing directory on the server. 
	 * The directory needs to have write permissions
	 *
	 * @access	public
	 * @param	string	input field name
	 * @param	string	directory path, defaults to "files"
	 * @param	string	desired name of the new file (if empty, a name will be auto generated)
	 * @return	string  name of the new uploaded file
	 */	
	 
    public function upload($name, $dir="files/", $newname="")
    {
	   if(!empty($_FILES["$name"]))
	   {
		  $name_filename=$_FILES["$name"]['name'];
	
	      //getting extension of the file
	      $parts=explode(".",$name_filename);
	      $extension=$parts[sizeof($parts)-1];
	      $extension=strtolower($extension);
	      
	      $extension=".$extension";      
	
	      if(empty($newname))
		  {
		     $newname=md5(time()).$extension;
		  }
		  
	      @copy($_FILES[$name]['tmp_name'],$dir.$newname);
	
	      }
	   return $newname;
    }
    
    
   /**	 
	 * Writes file from a data 
	 *
	 * @access	public
	 * @param	string	path and name of the file. The directory must exist
	 * @param	string	variable containing data to be writtem	 	 
	 */	
   public function write($path,$content)
   {
		$fp=fopen($path,"wb");
		fwrite($fp,$content);
		fclose($fp);
   }
    
   /**	 
	 * Deletes a file
	 *
	 * @access	public
	 * @param	string	path and name of the file	 
	 * @return	boolean
	 */	
	 
    public function delete($file)
    {
	    if(@unlink($file))
	    {
		    return true;
	    }
	    else
	    {
		    return false;
	    }
    }
    
    /**	 
	 * Check if directory exists. If not, creates it with write permissions
	 *
	 * @access	public
	 * @param	string	directory path
	 * @param	string	mode	 	 
	 */	
    
    public function havedir($dir,$mode='0777')
    {
	    if(!is_dir($dir))
	    {
		    @mkdir($dir,$mode);
		    @chmod($dir,$mode);
	    }
    }
}

// END File Utility class
?>