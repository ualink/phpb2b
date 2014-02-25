<?php
class Industry extends PbController {
	var $name = "Industry";
	var $names = array();
 	public static $instance = NULL;
 	private $ids;
 	
 	function __construct()
 	{
 		parent::__construct();
 		$this->loadModel("industry");
 	}
	
	function getNames()
	{
		return $this->names;
	}
	
	function setNames()
	{
		if(func_num_args()<1) return;
		$return  = array();
		$_PB_CACHE['industry'] = cache_read("industry");
		$args = func_get_args();
		foreach ($args as $key=>$val) {
			$return[] = isset($_PB_CACHE['industry'][$val])?$_PB_CACHE['industry'][$val]:'';
		}
		$this->names = $return;
	}	
	
	function getInstance() {
		if (!$instance) {
			$instance[0] = new Industry();
		}
		return $instance[0];
	}
	
	function getSubIds($id)
	{
		$this->ids[] = $id;
		$_tmp = $this->industry->getConditionIds($id);
		array_walk_recursive($_tmp, array($this, 'formatItemIds'));
		sort($this->ids);
		return $this->ids;
	}
	
	function formatItemIds($item, $key)
	{
		if($key=="id") $this->ids[] = $item;
	}
}
?>