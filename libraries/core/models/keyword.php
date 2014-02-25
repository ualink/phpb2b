<?php
class Keywords extends PbModel {
	var $name = "Keyword";
 	var $type_condition;
 	var $exist_keyword_id = array();
 	var $inserted_keyword_id = array();
 	var $keyword_id;
 	var $allow_models = array('newses','products','companies','trades');
 	var $ids = null;
 	var $target_ids = null;
 	public static $instance = NULL;

 	function __construct()
 	{
		parent::__construct();
 	}
 	
	function getInstance() {
		if (!isset(self::$instance[get_class()])) {
			self::$instance = new Keywords();
		}
		return self::$instance;
	} 	

	function checkKeywordExists($title)
	{
		$sql = "SELECT id FROM {$this->table_prefix}keywords WHERE title='{$title}";
		$result = $this->dbstuff->GetRow($sql);
		if ($result) {
			return true;
		}else{
			return false;
		}
	}

	function setKeywordId($keys, $prim_id, $type_id)
	{
	    if(!empty($keys)){
	        $words = str_replace(array("��", " ", "��"), ",", $keys);
	        $words = explode(",", $words);
	        foreach ($words as $key=>$val){
	            $val = trim($val);
	            $kid = $this->dbstuff->GetOne("select id from {$this->table_prefix}keywords where title='".$val."' and type='$type_id'");
	            if ($kid) {
	                $pid = $this->dbstuff->GetOne("select primary_id from {$this->table_prefix}keywords where id=".$kid);
	                if ($pid) {
	                    $exist_ids = explode(",", $pid);
	                    if(!in_array($prim_id, $exist_ids)) {
	                        $exist_ids[] = $prim_id;
	                        $exist_ids = implode(",", $exist_ids);
	                        $this->dbstuff->Execute("update {$this->table_prefix}keywords set primary_id='".$exist_ids."' where id=".$kid);
	                        $this->exist_keyword_id[] = $kid;
	                    }
	                }else{
	                    $this->dbstuff->Execute("update {$this->table_prefix}keywords set primary_id='".$prim_id."' where id=".$kid);
	                    $this->exist_keyword_id[] = $kid;
	                }
	            }else{
	                $this->dbstuff->Execute("insert into {$this->table_prefix}keywords (title,primary_id,member_id,type,created) values ('$val','$prim_id','".$_SESSION['MemberID']."','$type_id','".date("Y-m-d H:i:s")."')");
	                $this->inserted_keyword_id[] = $this->dbstuff->Insert_ID();
	            }
	        }
	    }
		unset($exist_ids, $kid, $pid, $words);
	}

	function getKeywordId()
	{
		$ids = array_merge($this->exist_keyword_id, $this->inserted_keyword_id);
		$ids = implode(",", $ids);
		return $ids;
	}
	
	function checkSegmentwordExists($word){
		$sql = "SELECT id FROM {$this->table_prefix}segmentwords WHERE name='{$word}";
		$result = $this->dbstuff->GetRow($sql);
		if ($result) {
			return true;
		}else{
			return false;
		}
	}
	
	function getKeywordsByIds($keywords)
	{
		$sql = "SELECT title FROM {$this->table_prefix}keywords WHERE id IN (".$keywords.")";
		$result = $this->dbstuff->GetArray($sql);
		return $result;
	}
 	
 	function array_diff($array_1, $array_2) {
 		$array_2 = array_flip($array_2);
 		foreach ($array_1 as $key => $item) {
 			if (isset($array_2[$item])) {
 				unset($array_1[$key]);
 			}
 		}
 		return $array_1;
 	}
 	
 	function setIds($str, $type_name = 'trades', $add_new = false, $target_id = 0)
 	{
		//only for chinese.
		if(file_exists(LOCALE_PATH. 'wordsegment.class.php')){
			require(LOCALE_PATH. 'wordsegment.class.php');
			$ws = new WordSegment();
			$ws->zhcode($str);
			$title = $ws->getResult();
		}else $title = $str;
 		$values = $exist_keys = $not_exist_keys = $key_result = array();
 		if (empty($title) || !is_array($title)){
 			return false;
 		}
 		$titles = implode("','", $title);
 		$key_result = $this->dbstuff->GetArray("SELECT id,title FROM {$this->table_prefix}keywords WHERE title IN ('{$titles}') AND type_name='".$type_name."' AND target_id='".$target_id."'");
 		if(!empty($key_result)){
	 		foreach ($key_result as $val){
	 			$exist_keys[] = $val['title'];
	 		}
 		}
 		$not_exist_keys = $this->array_diff($title, $exist_keys);
 		if (!empty($not_exist_keys)) {
 			foreach ($not_exist_keys as $valx) {
 				$values[] = "('{$valx}',{$target_id},'{$type_name}')";
 			}
	 		if($add_new && !empty($values)) {
	 			$values = implode(",", $values);
	 			$this->dbstuff->Execute("INSERT INTO {$this->table_prefix}keywords (title,target_id,type_name) VALUES ".$values);
	 		}
 		}
 	}
 	
 	function getIds()
 	{
 		return $this->ids;
 	}
}