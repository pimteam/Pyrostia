<?php
/* Celeroo Frame Basic Model Class. Do not remove. */
abstract class Basic
{
	protected $dependencies;

	function __construct()
	{
		global $prefix;        
		$this->DB=get_db_connection();
		$this->tablename=$prefix.strtolower(get_class($this)).'s';
		$this->security=array("sql"=>TRUE, "html"=> 0);
		$this->context=get_context();
		$this->dependencies=array();
	}
	
	//Add new record
	public function add($vars)
	{
		$vars=$this->clean_input($vars);
		$last_id=$this->DB->dumpto_table($this->tablename,$vars);
		
		if(!$this->context->get("keep_cache")) 
		{
			CacheHelper::clear($this->tablename, $this->dependencies);
		}
		
		return $last_id;
	} 
	// end add()
	
	// Edit record
	public function edit($vars,$id)
	{           
		if(!is_numeric($id)) die("The ID parameter can be only numeric");
		$vars=$this->clean_input($vars);
		$this->DB->update_table($this->tablename,$vars,"id",$id);
		
		if(!$this->context->get("keep_cache")) 
		{
			CacheHelper::clear($this->tablename, $this->dependencies);
		}
	} // end edit() method
	
	//delete record
	public function delete($id)
	{
		if(!is_numeric($id)) die("The ID parameter can be only numeric");
		$q="DELETE FROM `".$this->tablename."` WHERE id=\"$id\"";
		$this->q($q);
	}
	
	//view record
	public function view($id)
	{
		if(!is_numeric($id)) die("The ID parameter can be only numeric");
		$q="SELECT * FROM ".$this->tablename." WHERE id=\"$id\"";
		$results=$this->sq($q);

		return $results;
	}
	
	// quick and easy funciton to retrieve a record or a set of records	
	/*
	* If $key>0 the funciton searches for specific record with id=$key
	* if $key is an array the function searches for specific record with $key[field]=$key[value]
	* if $key==0 the function searches for multiple records using the $options array
	* if $key==-1 the function uses the $options array but returns a single record
	* if $options[page_limit]==0 the function returns all the records (not paginated) 
	* but also returns the count
	* If $options[page_limit]==-1 the function returns all the records only, no count
	*/
	function find($key=0,$options=NULL, &$result_info=NULL)
	{
	    global $offset;
		
		if($result_info==NULL)
		{
			$result_info=array();
		}
		
		if(!isset($options['fields']) or empty($options['fields'])) $options['fields']="*";		
		
		if(isset($options['conditions']) and !empty($options['conditions']))
		{
			$where_sql=" WHERE $options[conditions] ";
		}
		
		if(!isset($options['from_tables']))
		{
			$options['from_tables']=$this->tablename;
		}
			
		if($key>0 and !is_array($key))
		{
			if(!is_numeric($key)) die("ID can be only numeric");
			
			$result_info['result_type']="SINGLE";
		
			// single record by id
			$q="SELECT $options[fields] FROM $options[from_tables] WHERE id='$key'";
			$row=$this->sq($q);
			return $row;
		}
		
		if(is_array($key))
		{
			SanitizeHelper::sql_safe($key['field']);	
			SanitizeHelper::sql_safe($key['value']);
			
			$result_info['result_type']="SINGLE";	
		
			// single record by other field
			$q="SELECT SQL_CALC_FOUND_ROWS $options[fields] FROM $options[from_tables] WHERE $key[field]='$key[value]'";
			$row=$this->sq($q);
			return $row;
		}
		
		if($key<=0)
		{
			// multiple records
			if(!isset($options['page_limit'])) $options['page_limit']=PAGE_LIMIT;
			
			// global offset is used but not when $key==-1 (retrieving single record)
			if(!isset($options['offset']) and $key!=-1) $options['offset']=$offset;
			if(!isset($options['offset']) and $key==-1) $options['offset']=0;
			
			if(!isset($options['orderby'])) $options['orderby']="id";
			if(!isset($options['orderdir'])) $options['orderdir']="asc";			
			$where_sql="";
			
			$limit_sql="";
			
			if($options['page_limit'] > 0)
			{
				$limit_sql=" LIMIT $options[offset], $options[page_limit] ";
			}
			
			if(isset($options['conditions']) and !empty($options['conditions']))
			{
				$where_sql=" WHERE $options[conditions] ";
			}
			
			$having_sql="";
			if(isset($options['having']) and !empty($options['having']))
			{
				$having_sql=" $options[having] ";
			}
			
			$group_sql="";
			if(isset($options['groupby']))
			{
				$group_sql=" GROUP BY $options[groupby] ";
			}
			
			$q="SELECT SQL_CALC_FOUND_ROWS $options[fields] FROM $options[from_tables]
			$where_sql
			$group_sql
			$having_sql
			ORDER BY $options[orderby] $options[orderdir]
			$limit_sql";						
			$rows=$this->aq($q);
						
			if($key==-1)
			{ 
				$result_info['result_type']="SINGLE";
				return @$rows[0]; // to find a single record with conditions
			}
			elseif($options['page_limit']==-1) 
			{
				$result_info['result_type']="ARRAY";
				return $rows; // return non paginated rows
			}
			else 
			{
				$result_info['result_type']="COMPLEX";
			
				// run count query as well and return array (for pagination)
				$q="SELECT FOUND_ROWS()";
				$count=$this->oq($q);
				
				return array($rows,$count);
			}
		}
	}
		
	
	// cleans input before inserting into the database
	private function clean_input($vars)
	{
		if(isset($this->unsets) and is_array($this->unsets))
		{
			foreach($vars as $key=>$var)
			{
				if(in_array($key,$this->unsets))
				{
					unset($var[$key]);
				}
			}
		}
	
		foreach($vars as &$var)
		{
			// don't touch arrays
			if(is_array($var)) continue;
		
			if(@$this->security['sql'])
			{
				SanitizeHelper::sql_safe($var);	
			}
			
			if(@$this->security['paranoid'])
			{
				SanitizeHelper::paranoid($var);	
			}
			
			SanitizeHelper::html_safe($this->security['html']);
		}
		
		return $vars;
	}
	
	// replication of $DB function because of caching
	function q($q)
	{
		// clear all caches for this table
		if(!$this->context->get("keep_cache")) 
		{
			CacheHelper::clear($this->tablename, $this->dependencies);
		}
		
		if($this->context->get("debug")) echo $q."\n\n";
	
		try
		{
			return $this->DB->q($q);	
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	// wrapper for DB->oq, handles cache, exceptions etc
	function oq($q)
	{
		if($this->context->get("cache"))
		{
			$success=true;
			$data=CacheHelper::fetch($this->tablename, $q, $success);
			if($success) return $data;
		}
		
		if($this->context->get("debug")) echo $q."\n\n";
		
		try
		{
			$data=$this->DB->oq($q);	
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}		
		
		if($this->context->get("cache"))
		{
			CacheHelper::store($data, $this->tablename, $q);			
		}
		
		return $data;
	}
	
	// wrapper for DB->sq, handles cache, exceptions etc
	function sq($q)
	{		
		if($this->context->get("cache"))
		{
			$success=true;
			$data=CacheHelper::fetch($this->tablename, $q, $success);
			if($success) return $data;
		}
		
		if($this->context->get("debug")) echo $q."\n\n";
			
		try
		{
			$data=$this->DB->sq($q);	
		}
		catch(Exception $e)
		{
			die(nl2br($e->getMessage()));
		}
		
		if($this->context->get("cache"))
		{
			CacheHelper::store($data, $this->tablename, $q);			
		}
		
		return $data;
	}
	
	// wrapper for DB->aq, handles cache, exceptions etc
	function aq($q)
	{	
		if($this->context->get("cache"))
		{	
			$success=true;
			$data=CacheHelper::fetch($this->tablename, $q, $success);			
			if($success) return $data;
		}
		
		if($this->context->get("debug")) echo $q."\n\n";
				
		try
		{
			$data=$this->DB->aq($q);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
		
		if($this->context->get("cache"))
		{
			CacheHelper::store($data, $this->tablename, $q);			
		}
		
		return $data;
	}
}
?>