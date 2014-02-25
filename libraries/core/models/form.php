<?php
class Forms extends PbModel {
 	var $name = "Form";
	var $form_id;
	var $attribute_id;

 	function __construct()
 	{
 		parent::__construct();
 	}
	
	function setFormId($item_id)
	{
		$this->form_id = $item_id;
	}
	
	function getFormId()
	{
		return $this->form_id;
	}
	
	function getAttribute($attribute_ids = 0, $form_id = 1)
	{
		$_PB_CACHE['form'] = cache_read("form");
		$result = $data = array();
		$condition = null;
		if (!empty($attribute_ids)) {
			if (is_array($attribute_ids)) {
				$tmp = implode(",", $attribute_ids);
				$tmp = "fb.id IN (".$tmp.")";
			}else{
				$tmp = "fb.id=".intval($attribute_ids);
			}
			$condition = " WHERE ".$tmp;
			$sql = "SELECT formitem_id,attribute FROM {$this->table_prefix}formattributes fb{$condition}";
			$result = $this->dbstuff->GetArray($sql);
			if (!empty($result)) {
				foreach ($result as $key=>$val) {
					$data[$_PB_CACHE['form'][$form_id][$val['formitem_id']]['id']] = $val['attribute'];
				}
			}
		}
		return $data;	
	}
	
	function getAttributes($attribute_ids = 0, $form_id = 1)
	{
		$_PB_CACHE['form'] = cache_read("form");
		$result = $data = array();
		$condition = null;
		$data = $_PB_CACHE['form'][$form_id];
		if (!empty($attribute_ids)) {
			if (is_array($attribute_ids)) {
				$tmp = implode(",", $attribute_ids);
				$tmp = "id IN (".$tmp.")";
			}else{
				$tmp = "id=".intval($attribute_ids);
			}
			$condition = " WHERE ".$tmp;
			$sql = "SELECT id,form_id,formitem_id,attribute FROM {$this->table_prefix}formattributes{$condition}";
			$result = $this->dbstuff->GetArray($sql);
		}
		if (!empty($result)) {
			foreach ($result as $key=>$val) {
				$tmp_result[$val['formitem_id']] = $val['attribute'];
			}
			foreach ($data as $key=>$val) {
				if (!empty($tmp_result[$key])) {
						$data[$key]['value'] = $tmp_result[$key];
				}	
			}
		}
		return $data;
	}
	
	/**
	 * default:offer form
	 *
	 * @param unknown_type $primary_id
	 * @param unknown_type $form_attributes
	 * @param unknown_type $form_id
	 * @param unknown_type $type_id
	 * @return unknown
	 */
	function Add($primary_id, $form_attributes, $form_id=1, $type_id = 1)
	{
		$datas = array();
		$inserts = null;
		$reurn_attribute_ids = null;
		$form_attributes = array_filter($form_attributes);
		if (!empty($form_attributes) && is_array($form_attributes)) {
			foreach ($form_attributes as $key=>$val) {
				if($attribute_id = $this->dbstuff->GetOne("SELECT id FROM {$this->table_prefix}formattributes f WHERE primary_id={$primary_id} AND formitem_id={$key} AND type_id={$type_id} AND form_id={$form_id}")){
					$this->dbstuff->Execute("UPDATE {$this->table_prefix}formattributes SET attribute='{$val}' WHERE primary_id={$primary_id} AND formitem_id={$key} AND type_id={$type_id} AND form_id={$form_id}");
				}else{
					$datas[] = "(".$type_id.",".$form_id.",".$key.",".$primary_id.",'".$val."')";
				}
			}
			if(!empty($datas)){
				$inserts = implode(",", $datas);
				$sql = "INSERT INTO {$this->table_prefix}formattributes (type_id,form_id,formitem_id,primary_id,attribute) values {$inserts}";
				$this->dbstuff->Execute($sql);
			}
			$tmp_result = $this->dbstuff->GetArray("SELECT id FROM {$this->table_prefix}formattributes WHERE primary_id=".$primary_id." AND type_id={$type_id} ORDER BY id ASC");
			if (!empty($tmp_result)) {
				foreach ($tmp_result as $key=>$val) {
					$reurn_attribute_ids[] = $val['id'];
				}
				return implode(",", $reurn_attribute_ids);
			}else{
				return false;
			}
		}
		return false;
	}
}
?>