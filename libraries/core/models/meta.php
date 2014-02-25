<?php
class Metas extends PbModel {
	var $name = "Meta";
	
 	function __construct()
 	{
 		parent::__construct();
 	}

	/**
	 * add meta info
	 *
	 * @param object id $obj_id
	 * @param object type, product, trade, market $obj_name
	 * @param meta key, keyword, description, title $meta_type
	 * @param meta value $meta_content
	 */
	function add($obj_id, $obj_name, $meta_type, $meta_content = null)
	{
		//check exists.
		if($info = $this->dbstuff->GetRow("SELECT * FROM ".$this->table_prefix."metas WHERE object_id='".$obj_id."' AND object_type='".$obj_name."_".$meta_type."'")){
			$sql = "UPDATE ".$this->table_prefix."metas SET content='".$meta_content."' WHERE id='".$info['id']."'";
		}else{
			$sql = "INSERT INTO ".$this->table_prefix."metas (object_id,object_type,content) VALUES ('".$obj_id."','".$obj_name."_".$meta_type."','".$meta_content."')";
		}
		$result = $this->dbstuff->Execute($sql);
		if (!$result) {
			flash('failed');
		}
	}
	
	function save($obj_name, $obj_id, $data)
	{
		if (empty($data)) {
			return false;
		}
		foreach ($data as $key=>$val) {
			if (in_array($key, array('title', 'keyword', 'description'))) {
				$this->add($obj_id, $obj_name, $key, $val);
			}	 	
		}
	}
	
	function getSEOById($obj_id, $obj_name, $with_prefix = true)
	{
		$ret = array();
		$row = $this->dbstuff->GetArray("SELECT * FROM ".$this->table_prefix."metas WHERE object_id='".$obj_id."' AND object_type IN ('".$obj_name."_title','".$obj_name."_keyword','".$obj_name."_description')");
		if (!empty($row)) {
			foreach ($row as $key=>$val) {
				$seo_key = $with_prefix?str_replace($obj_name.'_', 'seo_', $val['object_type']):str_replace($obj_name.'_', '', $val['object_type']);
				$ret[$seo_key] = $val['content'];
			}
		}
		unset($row);
		return $ret;
	}
}
?>