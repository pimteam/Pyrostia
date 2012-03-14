<?php
/*
Database wrapper class for mysql
*/

class DBClass
{   
    function __construct()
    {   	
        $this->Server ="localhost";
        $this->DB = "pyrostia";
        $this->User = "root";
        $this->Password = "";
		$this->context=get_context();

        if(!mysql_connect($this->Server, $this->User, $this->Password))
		{
			throw new Exception(mysql_error());			
		} 			
		
		mysql_query("SET CHARACTER SET 'utf8'"); 

        if(!mysql_select_db($this->DB))
		{
			throw new Exception(mysql_error());	
		}					
    }
	
	// a small mysql_query wrapper
	private function db_query($q)
	{
		$result=mysql_query($q);
		
		if(!$result)
		{
			throw new Exception($q."<br>".mysql_error());	
		}
		
		return $result;
	}

    /*!
      Execute a query on the global SQL database link.  
    */
    function q($sql, $destroy = false)
    {        
        return $this->db_query($sql);
    }

    /*!
      Executes a SELECT query that returns multiple rows and puts the results into the passed
      array as an indexed associative array.  The array is cleared first.  The results start with
      the array start at 0, and the number of results can be found with the count() function.
    */
    function aq($sql)
    {
        $array = array();
        $result=$this->q($sql);
		
        while($row=mysql_fetch_array($result))
        {
		   foreach($row as $key=>$value)
           {
          		$row[$key]=stripslashes($value);
           }
           //echo $row;
           array_push($array,$row);
        }
        return $array;
    }

    /*!
      Same as aq() but expects to receive 1 row only (no array), no more no less.
    */
    function sq( $sql )
    {	    
        $result=$this->q($sql);		
        $row=mysql_fetch_array($result);
		
        if(mysql_num_rows($result)>0){
			foreach($row as $key=>$value)
           {
          	 $row[$key]=stripslashes($value);
           }
		}
		return $row;
    }

    /*!
      Same as sq() but returns only one value directly, not as an array
    */
	function oq($sql)
	{
        $array = array();
        $ret = $this->array_query_append( $array, $sql, 1, 1 );
        $row = @$array[0];
        return $row[0];
	}

    /*!
      Differs from the above function only by not creating av empty array,
      but simply appends to the array passed as an argument.
     */
    function array_query_append( &$array, $sql, $min = 0, $max = -1 )
    {	    
        $result =& $this->q( $sql );
      
        @$offset = count( $array );

        if ( mysql_num_rows( $result ) > 0 )
        {
            for($eh = 0; $eh < mysql_num_rows($result); $eh++)
                $array[$eh + $offset] =& mysql_fetch_array($result);
        }
	
		mysql_free_result($result);
    }

     /*!
      Returns the ID of the last inserted row.
    */
    function lid()
    {
	   return mysql_insert_id();	 
    }

    /*!
      \static

      Closes the database connection.
    */
    function close()
    {
	    mysql_close();        
    }
    
    //get count
	function rcount($table,$where_sql="")
    {
	    $q="SELECT COUNT(id) FROM $table
	    $where_sql";
	    $count=$this->oq($q);
	    
	    return $count;
    }
	
	/**
 	* Dumps array of values in a DB table
 	*
 	* An easy way to do an insert query. All fields that exist 
 	* both in the table and in the passed array will be included in the query.
 	*
 	* @access	public
	 * @param   string
 	* @param   array
 	* @return	true
	 */	
	function dumpto_table($table,$vars)
	 {
		//select fields from table
		$q="SHOW FIELDS FROM $table";
		$fields=$this->aq($q);

		$q="INSERT IGNORE INTO $table (";

		foreach($fields as $field)
		{
			if(isset($vars[$field['Field']]))
			{
				$q.="`".$field['Field']."`,";
			}
		}

		$q=substr($q,0,strlen($q)-1);
		$q.=") VALUES (";

		foreach($fields as $field)
		{
			if(isset($vars[$field['Field']]))
			{
				if(is_array($vars[$field['Field']]))
				{
					$valstr=implode("|",$vars[$field['Field']]);
					if (!ini_get('magic_quotes_gpc')) $valstr=mysql_real_escape_string($valstr);
					$q.="\"$valstr\",";
				}
				else
				{	
					if (!ini_get('magic_quotes_gpc'))
					{
						$vars[$field['Field']]=mysql_real_escape_string($vars[$field['Field']]);
					}				
					$q.="\"".$vars[$field['Field']]."\",";
				}
			}
		}
		$q=substr($q,0,strlen($q)-1);
		$q.=")";
		$this->q($q);
	
		$lid=$this->lid();
		return $lid;
	}

	/**
 	* Updates a DB table with the values in a passed array
 	*
	 * An easy way to do an update query. All fields that exist 
 	* both in the table and in the passed array will be included in the query.
 	*
	 * @access	public
 	* @param   string
 	* @param   array
 	* @param   string
 	* @param   mixed
 	* @return	true
	 */	
	function update_table($table,$vars,$fld,$vle)
	{
		//select fields from table
		$q="SHOW FIELDS FROM $table";
		$fields=$this->aq($q);

		$q="UPDATE $table SET ";

		foreach($fields as $field)
		{
			if(isset($vars[$field['Field']]))
			{
				if(is_array($vars[$field['Field']]))
				{
					$valstr=implode("|",$vars[$field['Field']]);
					if (!ini_get('magic_quotes_gpc')) $valstr=mysql_real_escape_string($valstr);
					$q.="`$field[Field]`=\"$valstr\", ";
				}
				else
				{
					if (!ini_get('magic_quotes_gpc'))
					{
						$vars[$field['Field']]=mysql_real_escape_string($vars[$field['Field']]);
					}
					$q.="`$field[Field]`=\"".$vars[$field['Field']]."\", ";				
				}
			}
		}

		$q=substr($q,0,strlen($q)-2);
	
		$q.=" WHERE $fld='$vle' ";

		$this->q($q);
  }
}
?>