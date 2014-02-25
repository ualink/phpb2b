<?php
class Payments extends PbModel {
 	var $name = "Payments";
 	
    function __construct()
    {
    	parent::__construct(); 
    }

	function install($entry)
	{
		$tpldir = realpath($this->payment_path.$entry);
		if (is_dir($tpldir)) {
			$this->params['data']['name'] = $entry;
			$this->params['data']['title'] = strtoupper($entry);
			$this->params['data']['available'] = 1;
			$this->params['data']['created'] = $this->params['data']['modified'] = $_SERVER['REQUEST_TIME'];
			$this->save($this->params['data']);
		}
	}
	
	function uninstall($id)
	{
		$sql = "DELETE FROM {$this->table_prefix}payments WHERE id=".$id;
		return $this->db->Execute($sql);
	}
	
	function getInstalled()
	{
		$sql = "SELECT * FROM {$this->table_prefix}payments WHERE available=1";
		$result = $this->db->GetArray($sql);
		return $result;
	}
}
?>