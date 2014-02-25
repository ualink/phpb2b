<?php
class Dicttypes extends PbModel {
 	
 	var $name = "Dicttype";
 	var $data;
 	var $typeOptions;
 	var $hasChildren;

 	function __construct()
 	{
 		parent::__construct();
 	}
 	
 	function disSubOptions($parent_id, $level)
 	{
 		$data = $this->findAll("*", null, "parent_id='".$parent_id."'", "display_order ASC");
 		if (!empty($data)) {
 			$this->hasChildren=true;
 			foreach ($data as $key=>$val) {
 				$val['name'] = pb_lang_split($val['name']);
 				$this->typeOptions.='<option value="'.$val['id'].'">';
 				$this->typeOptions.=str_repeat('&nbsp;&nbsp;', $level) . $val['name'];
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
 	
 	function getAllTypes()
 	{
 		$data = array();
 		$data = $this->findAll("*", null, "parent_id=0", "display_order ASC");
 		if (!empty($data)) {
 			for($i=0; $i<count($data); $i++) {
 				$sub_data = $this->dbstuff->GetArray("SELECT * FROM {$this->table_prefix}dicttypes WHERE parent_id='".$data[$i]['id']."'");
 				if ($sub_data) {
 					$data[$i]['sub'] = $sub_data;
 				}
 			}
 		}
 		return $data;
 	}
}
?>