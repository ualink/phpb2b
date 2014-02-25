<?php
class Newstypes extends PbModel {

 	var $name = "Newstype";
 	var $data;
 	var $typeOptions;
 	var $hasChildren;

 	function __construct()
 	{
		parent::__construct();
 	}
 	
 	function disSubOptions($parent_id, $level)
 	{
 		$data = $this->findAll("*", null, "parent_id='".$parent_id."'", "id ASC");
 		if (!empty($data)) {
 			$this->hasChildren=true;
 			foreach ($data as $key=>$val) {
 				$this->typeOptions.='<option value="'.$val['id'].'">';
 				$this->typeOptions.=str_repeat('&nbsp;&nbsp;', $level) . pb_lang_split($val['name']);
 				$this->typeOptions.='</option>' . "\n";
 				$this->disSubOptions($val['id'], $level+1);
 			}
 		}else{
 			$this->hasChildren=false;
 		}
 	}
 	
 	function getTypeOptions()
 	{
 		$this->typeOptions = '';
 		$this->disSubOptions(0, 0);
 		return $this->typeOptions;
 	}
 	
 	function getCacheTypes()
 	{
 		if (!isset($_PB_CACHE['newstype'])) {
 			$_PB_CACHE['newstype'] = cache_read("type", "newstype");
 		}
 		return $_PB_CACHE['newstype'];
 	}
}
?>