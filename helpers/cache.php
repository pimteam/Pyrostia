<?php
class CacheHelper
{
	// store value in cache
	public static function store($var, $table, $query)	
	{
		// write var to file
		$var=serialize($var);
		$filename=$table."-cache-".md5($query).".cache";
		
		$fp=fopen("cache/".$filename, "wb");
		if (flock($fp, LOCK_EX))
		{
			fwrite($fp,$query."###SEPARATOR###".$var);	
			flock($fp, LOCK_UN); 			
		}		
		fclose($fp);
	}
	
	// fetch value from cache
	public static function fetch($table, $query, &$success)	
	{
		$filename=$table."-cache-".md5($query).".cache";
	
		$content=@file_get_contents("cache/".$filename);
		$parts=explode("###SEPARATOR###",$content);
		$var=@$parts[1];
		
		// let's check if this cache is valid - because md5 gives no guarantee of uniqueness
		if(strcmp($query, $parts[0])!=0)
		{
			$success=false;
			return false;
		}
		
		// can't open file or it's older than 1 day
		if($var===false or (@filemtime("cache/".$filename) < time()-24*3600) ) 
		{
			$success=false;
			return false;
		}
		
		$var=unserialize($var);
		
		// echo $parts[0]." FROM CACHE<br><br>";
				
		return $var;
	}
	
	// clear all caches for a given table
	public static function clear($table, $dependencies)
	{
		// clear all cashes for the given table
		if ($handle = @opendir('cache/')) 
		{
		    while (false !== ($file = readdir($handle))) {
			
				if(strcmp($file,".htaccess")==0) continue;
			
				if(filemtime("cache/".$file) < time()-24*3600)
				{
					@unlink("cache/".$file);
					continue;
				}
				
		        if(preg_match("/^".$table."-cache-/", $file))
				{
					@unlink("cache/".$file);
					continue;
				}
				
				// check also all dependencies
				foreach($dependencies as $dependency)
				{
					 if(preg_match("/^".$dependency."-cache-/", $file))
					{
						@unlink("cache/".$file);
					}
				}
		    }		
		    closedir($handle);
		}
	}
}
?>