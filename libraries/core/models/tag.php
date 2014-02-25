<?php
class Tags extends PbModel {
	var $name = "Tag";
 	var $tag;
 	var $exist_id = array();
 	var $inserted_id = array();
 	var $id;
 	public static $instance = NULL;
 	

 	function __construct()
 	{
		parent::__construct();
 	}
 	
 	function Add($title)
 	{
		global $current_adminer_id, $G;
 		$title = pb_strip_spaces(strip_tags($title));
 		$exists = $this->checkTagExists($title);
 		$closed = 1;//invisible
 		if (isset($G['setting']['tag_check']) && $G['setting']['tag_check']==0) {
 			$closed = 0;
 		}
 		if ($exists) {
 			$this->dbstuff->Execute("UPDATE ".$this->table_prefix."tags SET numbers=numbers+1 WHERE id=".$this->id);
 			return false;
 		}else{
			if (!empty($current_adminer_id)) {
				$member_id = $current_adminer_id;
			}elseif (!empty($_SESSION['MemberID'])){
				$member_id = $_SESSION['MemberID'];
			}else{
				$member_id = 0;
			}
 			$sql = "INSERT INTO ".$this->table_prefix."tags (member_id,name,numbers,closed,created,modified) VALUE ('".$member_id."','".$title."',1,'".$closed."',".$this->timestamp.",".$this->timestamp.")";
 			return $this->dbstuff->Execute($sql);
 		}
 	}
 	
	function getInstance() {
		if (!isset(self::$instance[get_class()])) {
			self::$instance = new Tags();
		}
		return self::$instance;
	}

	function checkTagExists($title)
	{
		$sql = "SELECT id FROM ".$this->table_prefix."tags WHERE name='".$title."'";
		$result = $this->dbstuff->GetRow($sql);
		if (!empty($result)) {
			$this->id = $result['id'];
			return true;
		}else{
			return false;
		}
	}

	function setTagId($tags)
	{
		global $current_adminer_id;
		$tmp_exist_tag = array();
		if (empty($tags) || !$tags) {
			return;
		}
		$words = str_replace(array("，"), ",", trim($tags));
		if (strstr($words, ",")) {
			$words = explode(",", $words);
		}else{
			$words = explode(" ", $words);
		}
		$tmp_str = "('".implode("','", $words)."')";
		$result = $this->dbstuff->GetArray("SELECT id,name FROM {$this->table_prefix}tags WHERE name IN ".$tmp_str);
		if (!empty($result)) {
			foreach ($result as $key=>$val){
				$this->exist_id[] = $val['id'];
				$tmp_exist_tag[] = $val['name'];
			}
		}
		$not_exist_tag = array_diff($words, $tmp_exist_tag);
		if (!empty($not_exist_tag)) {
			$tmp_str = array();
			foreach ($not_exist_tag as $val2) {
				$this->Add($val2);
			}
			$result = $this->dbstuff->GetArray("SELECT id,name FROM {$this->table_prefix}tags WHERE name IN ('".implode("','", $not_exist_tag)."')");
			foreach ($result as $val3) {
				$this->inserted_id[] = $val3['id'];
			}
		}
		return $this->getTagId();
	}

	function getTagId()
	{
		$ids = array_merge($this->exist_id, $this->inserted_id);
		$ids = implode(",", $ids);
		if (empty($ids) || !$ids) {
			return '';
		}
		return $ids;
	}
	
	function getTagsByIds($tag_ids, $extra = false)
	{
		$return = array();
		if(empty($tag_ids) || !$tag_ids) return;
		$sql = "SELECT id,name FROM {$this->table_prefix}tags WHERE id IN (".$tag_ids.")";
		$result = $this->dbstuff->GetArray($sql);
		if (!empty($result)) {
			foreach ($result as $val){
				$return[$val['id']] = $val['name'];
			}
			if($extra) $this->tag = implode(" ", $return);
		}
		return $return;
	}
}