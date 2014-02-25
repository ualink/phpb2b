<?php
class Settings extends PbModel {
 	var $name = "Setting";
 	
 	function __construct()
 	{
 		parent::__construct();
 	}
 	
 	function getValue($var)
 	{
 		return $this->dbstuff->GetOne("SELECT valued FROM {$this->table_prefix}settings WHERE variable='".$var."'");
 	}

	function getValues($typeid = null, $var = null)
	{
		if (!is_null($typeid)) {
			$sql = "SELECT id,variable,valued FROM {$this->table_prefix}settings WHERE type_id='{$typeid}' ";
		}else{
			$sql = "SELECT id,variable,valued FROM {$this->table_prefix}settings";
		}
		$r_res = $this->dbstuff->GetArray($sql);
		$data = array();
		if (!empty($r_res)) {
    		foreach ($r_res as $key=>$value) {
    			$data[strtoupper($value['variable'])] = $value['valued'];
    		}
		}
		return $data;
	}
	
	function replace($datas, $typeid = 0)
	{
		$updated = false;
		$data = null;
		$values = array();
		foreach ($datas as $key=>$val) {
			$values[] = "('".$key."','".$val."','".$typeid."')";
		}
		$data = implode(",", $values);
		$sql = "REPLACE INTO {$this->table_prefix}settings (variable,valued,type_id) values ".$data;
		$updated = $this->dbstuff->Execute($sql);
		return $updated;
	}
}