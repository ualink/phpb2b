<?php
class Trade extends PbController {
	var $name = "Trade";
	var $info;
	var $type_names;
	var $type_info;
	var $types;
 	public static $instance = NULL;
	
	function __construct()
	{
		
	}
 	
 	function getOfferExpires()
 	{
 		return cache_read("typeoption", "offer_expire");
 	}
 	
 	function getModulenameById($typeid)
 	{
 		$module_name = null;
 		$this->setTypeInfo($typeid);
 		switch ($typeid) {
 			default:
 				$module_name = urlencode($this->type_info['name']);
 				break;
 		}
 		return $module_name;
 	}
	
	function setInfoById($id)
	{
//		$_this = & Trades::getInstance();
		$_this = Trades::getInstance();
		$this->info = $_this->getInfoById($id);
	}
	
	function getInstance() {
		static $instance = array();
		if (!$instance) {
			$instance[0] = new Trade();
		}
		return $instance[0];
	} 	 	
	
	function getInfoById()
	{
		return $this->info;
	}
	
	function setTypeInfo($typeid)
	{
		$types = $this->getTradeTypes();
		if (in_array($typeid, array_keys($types))) {
			$this->type_info['name'] = $this->types[$typeid];
		}else{
			$this->type_info['name'] = L("offer", 'tpl');
		}
	}
	
	function getTypeInfo()
	{
		return $this->type_info;
	}
	
 	function getTradeTypes()
 	{
		$this->types = cache_read("type", "offertype");
		return $this->types;
 	}

	function getTradeTypeNames(){
		return $this->type_names;
	}

	function setTradeTypeNames(){
		$this->type_names = cache_read("type", "offertype");
	}

 	function Expired($expire_time)
 	{
 		$tmp_day = mktime(0,0,0,date("m") ,date("d"),date("Y"));
 		if ($tmp_day > $expire_time) {
 			return true;
 		}else {
 			return false;
 		}
 	}
}
?>