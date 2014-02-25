<?php
class Typeoptions extends PbModel {
 	var $name = "Typeoption";

 	function __construct()
 	{
 		parent::__construct();
 	}
 	
 	function copy($table, $truncate = false, $coverage = true)
 	{
 		$table = $this->table_prefix.$table;
 		if ($truncate) {
 			$this->dbstuff->Execute("TRUNCATE TABLE ".$table);
 		}
 		if ($coverage) {
 			$sql = "REPLACE INTO {$table} (id,parent_id,level,name,display_order) select id,parent_id,level,name,display_order FROM ".$this->table_prefix."industries";
 		}else{
 			$sql = "INSERT INTO {$table} (parent_id,level,name,display_order) select parent_id,level,name,display_order FROM ".$this->table_prefix."industries";
 		}
		$result = $this->dbstuff->Execute($sql);
		return $result;
 	}
}
?>