<?php
class Navs extends PbModel {
 	var $name = "Nav";

 	function __construct()
 	{
 		parent::__construct();
 	}
 	
 	function getNavs()
 	{
 		$sql = "SELECT id,name,description,url,target,display_order,highlight FROM {$this->table_prefix}navs WHERE status=1";
 		$result = $this->dbstuff->GetArray($sql);
 		if (!empty($result)) {
 			for ($i=0; $i<count($result); $i++) {
 				$result[$i]['style'] = parse_highlight($result[$i]['highlight']);
 			}
 		}
 		return $result;
 	}
}
?>