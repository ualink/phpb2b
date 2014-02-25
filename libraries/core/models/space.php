<?php
class Spaces extends PbModel {
 	var $name = "Space";
 	public static $instance = NULL;

 	function __construct()
 	{
 		parent::__construct();
 	}
 	
	function getInstance() {
		if (!isset(self::$instance[get_class()])) {
			self::$instance = new Spaces();
		}
		return self::$instance;
	}
	
	function getSpaceLinks($member_id, $company_id = 0)
	{
		$result = array();
		$condition = null;
		if (!empty($company_id)) {
			$condition = "AND company_id='{$company_id}'";
		}
		$sql = "SELECT id,title,url,is_outlink,description,logo,highlight FROM {$this->table_prefix}spacelinks s WHERE member_id='{$member_id}' {$condition} ORDER BY s.display_order ASC";
		$result = $this->dbstuff->GetArray($sql);//set and get db cache
		if (empty($result)) {
			return false;
		}else{
			for($i=0; $i<count($result); $i++){
				if (!$result[$i]['is_outlink']) {
					$result[$i]['url'] = URL.$result[$i]['url'];
				}
			}
		}
		return $result;
	}
}
?>