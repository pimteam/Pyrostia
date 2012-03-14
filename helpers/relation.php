<?php
/* this class handles the many-to-many relation between two tables */
class RelationHelper
{
	/*
	 * Inserts a number of relations in the table
	 * $table - the SQL table name of the table which contains the relations
	 * $selector - the "leading" object name (i.e. just first field in the sequence)
	 * $relator - the "following" object nane (i.e. just the second field in the sequence)
	 * $id - ID of the leading object for which we are inserting relations
	 * $rels - the array (usually coming with POST) of related IDs (often from checkboxes)
	*/
	public static function add($table, $selector, $relator, $id, $rels)
	{
		global $DB;
		if(!sizeof($rels)) return true;
	
		// construct add SQL
		$q="INSERT INTO $table (".$selector."_id, ".$relator."_id)	VALUES ";
		$sqls=array();
		
		foreach($rels as $rel)	
		{
			$sqls[]="($id, $rel)";
		}
		
		$insert_sql=implode(", ",$sqls);
		
		$q.=$insert_sql;
		$DB->q($q);
	}
	
	public static function edit($table, $selector, $relator, $id, $rels)
	{
		global $DB;
		
		$rels=is_array($rels)?$rels:array();
	
		// select existing relations
		$q="SELECT * FROM $table WHERE ".$selector."_id='$id'";		
		$existing_rels=$DB->aq($q);
		
		$to_delete=array();
		$to_remain=array();
		$to_add=array();
		
		foreach($existing_rels as $rel)
		{
			if(!in_array($rel[$relator."_id"], $rels)) $to_delete[]=$rel['id'];
			else $to_remain[]=$rel['id'];
		}
		
		if(sizeof($to_delete))
		{
			$q="DELETE FROM $table WHERE id IN (".implode(",",$to_delete).")";
			$DB->q($q);
		}
		
		// now add those that need to be added
		foreach($rels as $rel)
		{
			if(!in_array($rel,$to_remain))	$to_add[]=$rel;			
		}
		
		if(sizeof($to_add))
		{
			$q="INSERT INTO $table (".$selector."_id, ".$relator."_id)	VALUES ";
			$sqls=array();
			foreach($to_add as $add)
			{
				$sqls[]="($id, $add)";	
			}
			
			$q.=implode(", ",$sqls);
			
			$DB->q($q);
		}
	}
	
	public static function select($table,$selector,$relator,$id)
	{
		global $DB;
		
		$rels=array();
		$q="SELECT * FROM $table WHERE `".$selector."_id`='$id'";
		$rows=$DB->aq($q);
		
		foreach($rows as $row) $rels[]=$row[$relator."_id"];
		
		return $rels;
	}
}
// End RelationHelper class
?>