<?php
class Logs extends PbModel {
 	var $name = "Log";

 	function __construct()
 	{
		parent::__construct();
 	}
 	
 	function Add($data)
 	{
 		if (empty($data['created'])) {
 			$data['created'] = $this->timestamp;
 		}
 		return $this->dbstuff->Execute("INSERT INTO {$this->table_prefix}logs (handle_type,source_module,description,ip_address,created,modified) VALUE ('".$data['handle_type']."','".$data['source_module']."','".$data['description']."','".pb_get_client_ip()."','".$data['created']."','".$this->timestamp."')");
 	}
}
?>