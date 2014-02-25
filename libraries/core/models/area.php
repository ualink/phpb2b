<?php
class Areas extends PbModel {
	public $name = "Area";
 	public static $instance = NULL;
	public $typeOptions;

	function __construct()
	{
		parent::__construct();
	}
	
 	function setInfo($id)
 	{
 		$result = $this->dbstuff->GetRow("SELECT * FROM {$this->table_prefix}areas WHERE id='".$id."'");
 		if (!($result) || empty($result)) {
 			return null;
 		}else {
 			$_tmp = unserialize($result['description']);
 			if (!empty($_tmp[$GLOBALS['app_lang']])) {
 				$result['name'] = $_tmp[$GLOBALS['app_lang']];
 			}
 			$this->info = $result;
 			return $result;
 		}
 	}
 	
 	function getInfo()
 	{
 		return $this->info;
 	}	
	
	function getInstance() {
		if (!isset(self::$instance[get_class()])) {
			self::$instance = new Areas();
		}
		return self::$instance;
	}
	
	/**
	 * for 5.0
	 *
	 * @param unknown_type $id
	 * @return unknown
	 */
 	function getConditionIds($id)
 	{
 		$r = null;
 		$this->dbstuff->setFetchMode(ADODB_FETCH_ASSOC);
 		$sql = "SELECT id,name,url,parent_id,level FROM ".$this->table_prefix."areas WHERE available=1";
 		$result = $this->GetArray($sql);
 		if (!empty($result)) {
 			$r = pb_format_tree($result, $id);
 		}
 		return $r;
 	}
	
	function getSubDatas($id, $extra = false)
	{
		$return = $result = array();
		$row = $this->dbstuff->GetRow("SELECT * FROM {$this->table_prefix}areas WHERE id='".$id."'");
		if (!empty($row)) {
			$return[$id] = $row['name'];
			if($row['level']==1){
				$result = $this->dbstuff->GetArray("SELECT t2.id,t2.name FROM {$this->table_prefix}areas t1 LEFT JOIN {$this->table_prefix}areas t2 ON t2.parent_id=t1.id WHERE t1.id='".$row['id']."' OR t2.top_parentid=".$row['id']);
			}else{
				$result = $this->dbstuff->GetArray("SELECT t2.id,t2.name FROM {$this->table_prefix}areas t1 LEFT JOIN {$this->table_prefix}areas t2 ON t2.parent_id=t1.id WHERE t1.id='".$row['id']."'");
			}
		}
		if (!empty($result)) {
			foreach ($result as $key=>$val) {
				$return[$val['id']] = $val['name'];
			}
		}
		return $return;
	}	
	
	function getCacheArea()
	{
		$_PB_CACHE['area'] = cache_read("area");
		return $_PB_CACHE['area'];
	}
	
 	function disSubOptions($id, $prefix = "")
 	{
 		$r = array();
 		if (!empty($id)) {
 			$this->dbstuff->setFetchMode(ADODB_FETCH_ASSOC);
 			$sql = "SELECT * FROM ".$this->table_prefix."areas WHERE id=".$id;
 			$result = $this->GetRow($sql);
 			if (!empty($result)) {
	 			if ($result['level']==3) {
	 				$sql = "SELECT t1.id AS ".$prefix."id1, t2.id as ".$prefix."id2, t3.id as ".$prefix."id3 FROM ".$this->table_prefix."areas AS t1 LEFT JOIN ".$this->table_prefix."areas AS t2 ON t2.parent_id = t1.id LEFT JOIN ".$this->table_prefix."areas AS t3 ON t3.parent_id = t2.id WHERE t3.id = ".$id;
	 				$r = $this->GetRow($sql);
	 			}elseif ($result['level']==2){
	 				$sql = "SELECT t1.id AS ".$prefix."id1, t2.id as ".$prefix."id2 FROM ".$this->table_prefix."areas AS t1 LEFT JOIN ".$this->table_prefix."areas AS t2 ON t2.parent_id = t1.id WHERE t2.id = ".$id;
	 				$r = $this->GetRow($sql);
	 			}else{
	 				$r[$prefix.'id1'] = $id;
	 			}
 			}
 		}
 		return $r;
 	}
 	
 	function disSubNames($id, $sep = "&raquo;", $link = false, $do = null)
 	{
		$r = array();
 		$ids = $this->disSubOptions($id);
 		$tmp_controller = new PbController();
 		if (is_null($sep)) {
 			$sep = "&raquo;";
 		}
 		$_PB_CACHE['area'] = cache_read("area");
 		$datas = $tmp_controller->array_multi2single($_PB_CACHE['area']);
 		$tmp_ids = $ids;
 		foreach ($ids as $key=>$val) {
 			if($link){
 				$tmp = ltrim($key, "id");
 				switch ($tmp) {
 					case 1:
 						$the_id = implode(",", $ids);
 						break;
 					case 2:
 						unset($tmp_ids["id1"]);
 						$the_id = implode(",", $tmp_ids);
 						break;
 					default:
 						$the_id = $val;
 						break;
 				}
 				if (!function_exists("smarty_function_the_url")) {
 					require(SLUGIN_PATH."function.the_url.php");
 				}
 				$r[] = "<a href='".smarty_function_the_url(array("module"=>$do,"action"=>"lists", "do"=>"search", "areaid"=>$the_id))."' rel='special_link'>".$datas[$val]."</a>";
 			}else{
 				$r[] = $datas[$val];
 			}
 		}
 		unset($_PB_CACHE);
 		return implode($sep, $r);
 	}
 	
 	function getTypeOptions()
 	{
 		$_PB_CACHE['area'] = cache_read("area");
 		$typeOptions = '';
 		$this->dbstuff->setFetchMode(ADODB_FETCH_ASSOC);
 		$this->params['data'] = $this->dbstuff->GetArray("SELECT id,parent_id,name,level FROM ".$this->table_prefix."areas ORDER BY display_order ASC");
 		$tmp_arr = array();
 		foreach ($this->params['data'] as $key=>$val) {
 			$tmp_arr[$val['id']] = $this->params['data'][$key];
 		}
 		unset($key, $val);
 		foreach ($_PB_CACHE['area'][1] as $key=>$val) {
 			$typeOptions.='<option value="'.$key.'" class="option-level0">';
 			$typeOptions.=str_repeat('&nbsp;&nbsp;', 0) . $val;
 			$typeOptions.='</option>' . "\n";
 			foreach ($_PB_CACHE['area'][2] as $key2=>$val2) {
 				if ($tmp_arr[$key2]['parent_id'] == $key) {
		 			$typeOptions.='<option value="'.$key2.'" class="option-level1">';
		 			$typeOptions.=str_repeat('&nbsp;&nbsp;', 1) . $val2;
		 			$typeOptions.='</option>' . "\n";
		 			foreach ($_PB_CACHE['area'][3] as $key3=>$val3) {
		 				if ($tmp_arr[$key3]['parent_id'] == $key2) {
				 			$typeOptions.='<option value="'.$key3.'" class="option-level2">';
				 			$typeOptions.=str_repeat('&nbsp;&nbsp;', 2) . $val3;
				 			$typeOptions.='</option>' . "\n";
		 				}
		 			}
 				}
 			}
 		}
		$this->typeOptions = $typeOptions;
 		return $typeOptions;
 	}
}
?>