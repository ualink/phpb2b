<?php
class Membertypes extends PbModel {
 	var $name = "Membertype";
 	
 	function __construct()
 	{
 		parent::__construct();
 	}
 	
 	function getTypes()
 	{
 		return $this->dbstuff->GetArray("SELECT * FROM {$this->table_prefix}membertypes");
 	}
}
?>