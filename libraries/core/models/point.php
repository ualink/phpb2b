<?php
class Points extends PbModel {
 	var $name = "Point";
 	var $rules = array("every", "once", "daily", "weekly", "monthly", "yearly");
 	var $actions = array(
 		"logging"=>array("rule"=>"daily","do"=>"inc","point"=>1),
 	);
 	
 	function __construct()
 	{
 		parent::__construct();
 	}

	function increase($point, $member_id)
 	{
 		$this->dbstuff->Execute("UPDATE {$this->table_prefix}members SET points=points+{$point} WHERE id={$member_id}");
 	}
 	
 	function decrease($point, $member_id)
 	{
 		$this->dbstuff->Execute("UPDATE {$this->table_prefix}members SET points=points-{$point} WHERE id={$member_id}");
 	}
 	
 	function checkIfCanUpdate($member_id, $rule, $action)
 	{
 		$conditions = array();
 		if (!empty($member_id)) {
 			$conditions[] = "member_id={$member_id}";
 		}
 		$today_timestamp = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
 		switch ($rule) {
 			case "every":
 				return true;
 				break;
 			case "once":
 				$conditions[] =  "action_name='".$action."'";
 				break;
 			case "daily":
 				$conditions[] = "created>".$today_timestamp;
 				break;
 			case "weekly":
 				$conditions[] = "created>".($this->timestamp-7*86400);
 				break;
 			case "monthly":
 				$conditions[] = "created>".($this->timestamp-30*86400);
 				break;
 			case "yearly":
 				$conditions[] = "created>".($this->timestamp-365*86400);
 				break;
 			default:
 				break;
 		}
 		$this->setCondition($conditions);
 		$result = $this->dbstuff->GetRow("SELECT action_name,points,created FROM {$this->table_prefix}pointlogs".$this->getCondition());
 		if (!empty($result)) {
 			return false;
 		}else{
 			return true;
 		}
 	}
 	
 	function update($action, $member_id, $description = '')
 	{
 		if (array_key_exists($action, $this->actions) && !empty($member_id)) {
 			$rule = $this->actions[$action]['rule'];
 			$can_update = $this->checkIfCanUpdate($member_id, $rule, $action);
 			if($can_update){
 				$point = abs($this->actions[$action]['point']);
	 			switch ($this->actions[$action]['do']) {
	 				case "inc":
	 					$updated = $this->increase($point, $member_id);
	 					break;
	 				case "dec":
	 					$updated = $this->decrease($point, $member_id);
	 					break;
	 				default:
	 					break;
	 			}
	 			$sql = "INSERT INTO {$this->table_prefix}pointlogs (member_id,action_name,points,description,ip_address,created) VALUE ({$member_id},'".$action."',".$point.",'".$description."','".pb_get_client_ip('str')."',".$this->timestamp.")";
	 			$this->dbstuff->Execute($sql);
 			}else{
 				return;
 			}
 		}else{
 			return;
 		}
 	}
}
?>