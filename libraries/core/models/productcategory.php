<?php
class Productcategories extends PbModel {
 	
 	var $name = "Productcategory";
	var $typeOptions;

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
}
?>