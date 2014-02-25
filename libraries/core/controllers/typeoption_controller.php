<?php
class Typeoption extends PbController {
	var $name = "Typeoption";
	var $cacheFile = '';
	var $data = '';

	function __construct($file = null)
	{
		parent::__construct();
		$this->loadCache($file);
	}
	
	function loadCache($file = null)
	{
		if (empty($file)) {
			$this->cacheFile = CACHE_COMMON_PATH. "cache_typeoption.php";
		}else{
			$this->cacheFile = $file;
		}
		if (file_exists($this->cacheFile)) {
			require_once($this->cacheFile);
			$this->data = $_PB_CACHE;
		}
	}

	function get_cache_key_unique($type_cachenames, $val)
	{
		$tmp_keys = array_keys($this->data[$type_cachenames]);
		return intval(array_search($val, $tmp_keys));
	}

	function get_cache_type($cache_name, $key = NULL, $addParams = '')
	{
		if (!empty($addParams)) {
			if (is_array($addParams)) {
				foreach ($addParams as $val) {
					unset($this->data[$cache_name][$val]);
				}
			}else{
				unset($this->data[$cache_name][$addParams]);
			}
		}
		if (!is_null($key)) {
			return $this->data[$cache_name][$key];
		}else{
			return $this->data[$cache_name];
		}
	}
}
?>