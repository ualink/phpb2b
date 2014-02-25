<?php
class Tradefields extends PbModel {
 	var $name = "Tradefield";
 	public static $instance = NULL;
 	
 	function __construct()
 	{
 		parent::__construct();
 	}

	function getInstance() {
		if (!isset(self::$instance[get_class()])) {
			self::$instance = new Tradefields();
		}
		return self::$instance;
	}
	
	function replace($datas)
	{
		if (!empty($datas)) {
			$keys = array_keys($datas);
			$keys = "(".implode(",", $keys).")";
			$values = "('".implode("','", $datas)."')";
			$sql = "REPLACE INTO {$this->table_prefix}tradefields ".$keys." VALUES ".$values;
			return $this->dbstuff->Execute($sql);
		}
	}
}
?>