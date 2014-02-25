<?php
class Plugins extends PbModel {
 	var $name = "Plugin";
 	static private $_instance = null;

 	function __construct()
 	{
 		parent::__construct();
 	}

	function getInstance() {
		static $_instance=array();
		if(empty($_instance))
			$_instance[]= new Plugins();
		return $_instance[0];
	}
	
	function getInstalled(){
		$installed = array();
		$sql = "SELECT * FROM {$this->table_prefix}plugins GROUP BY name ORDER BY id DESC";
		$result = $this->dbstuff->GetArray($sql);
		if (!empty($result)) {
			$count = count($result);
			for($i=0; $i<$count; $i++){
				$result[$i]['available'] = 1;
			}
		}
		return $result;
	}	
}
?>