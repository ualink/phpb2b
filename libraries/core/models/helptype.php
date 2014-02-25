<?php
class Helptypes extends PbModel {
 	
 	var $name = "Helptype";
 	var $data;

 	function __construct()
 	{
 		parent::__construct();
 	}
 	
 	function getTypeOptions($selected = '', $level = 2)
 	{
 		$opt = null;
 		$helptypes = $this->findAll("*", null, null, "level ASC");
 		if (!empty($helptypes)) {
 			foreach ($helptypes as $ret) {
 				$this->data[$ret['id']] = $ret['title'];
 			}
 			for($i=1; $i<=$level; $i++){
 				$opt.='<optgroup label="'.L('type_level'.$i, 'tpl').'">';
 				foreach ($helptypes as $key=>$val){
 					if($val['level']==$i){
 						$opt.='<option value="'.$val['id'].'"';
						if($selected && $selected==$val['id']) $opt.=' selected="selected"';
						$opt.='">'.$val['title'].'</option>';
 					}
 				}
 				$opt.='</optgroup>';
 			}
 			return $opt;
 		}
 	}
}
?>