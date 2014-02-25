<?php
class Templets extends PbModel {
 	var $name = "Templet";
 	public static $instance = NULL;

 	function __construct()
 	{
 		parent::__construct();
 	}
 	
	function getInstance() {
//		if (!isset(self::$instance[get_class()])) {
		if (!isset(self::$instance)) {
			self::$instance = new Templets();
		}
		return self::$instance;
	}
	
	function getInstalled($membergroup_id = null, $membertype_id = null){
		$installed = $conditions = $free_result = array();
		if (isset($_GET['type']) && $_GET['type'] == "system") {
			$conditions[] = "t.type='".$_GET['type']."'";
			$this->setCondition($conditions);
			$condition = $this->getCondition();
		}else{
			$conditions[] = "t.type='user'";
			if (!empty($membergroup_id)) {
				$conditions[] = "INSTR(t.require_membergroups,'[".$membergroup_id."]')>0";
			}
			if (!empty($membertype_id)) {
				$conditions[] = "INSTR(t.require_membertype,'[".$membertype_id."]')>0";
			}
			$this->setCondition($conditions);
			$condition = $this->getCondition();
			//get free templets
			if(!defined("IN_PBADMIN")){
				$free_result = $this->dbstuff->GetArray("SELECT * FROM {$this->table_prefix}templets WHERE type='user' AND require_membergroups='0' AND status='1'");
			}
		}
		$sql = "SELECT t.* FROM {$this->table_prefix}templets t {$condition} ORDER BY t.id DESC";
		$request_result = $this->dbstuff->GetArray($sql);
		$result = array_merge($request_result, $free_result);
		if (!empty($result)) {
			$count = count($result);
			for($i=0; $i<$count; $i++){
				$result[$i]['picture'] = '';
				foreach ( array('png', 'gif', 'jpg', 'jpeg') as $ext ) {
					$_tmp = $result[$i]['directory'];
					if (strpos($_tmp, "templates")===false) {
						$_tmp = "templates/".$_tmp;
					}
					if (file_exists(PHPB2B_ROOT .$_tmp."screenshot.".$ext)) {
						$result[$i]['picture'] = URL.$_tmp."screenshot.".$ext;
						break;
					}
				}
				$result[$i]['available'] = 1;
			}
		}
		return $result;
	}

	function exchangeDefault($id)
	{
		$this->dbstuff->Execute("UPDATE {$this->table_prefix}templets SET is_default=0 WHERE type='system' AND id!='".$id."'");
		$this->dbstuff->Execute("UPDATE {$this->table_prefix}templets SET is_default=1 WHERE type='system' AND id='".$id."'");
	}
}
?>