<?php
class Area extends PbController {
	var $name = "Area";
	var $names = array();
 	public static $instance = NULL;
 	private $ids;
	
	function getNames()
	{
		return $this->names;
	}
	
	function getInstance() {
		if (!$instance) {
			$instance[0] = new Area();
		}
		return $instance[0];
	}
	
	function setNames()
	{
		if(func_num_args()<1) return;
		$return  = array();
		$_PB_CACHE['area'] = cache_read("area");
		$args = func_get_args();
		foreach ($args as $key=>$val) {
			$return[] = isset($_PB_CACHE['area'][$val]) ? $_PB_CACHE['area'][$val] : '';
		}
		$this->names = $return;
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