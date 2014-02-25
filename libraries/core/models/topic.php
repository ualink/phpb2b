<?php
class Topics extends PbModel {
 	var $name = "Topic";

 	function __construct()
 	{
 		parent::__construct();
 	}
 	
 	function addNews($topic_id, $data)
 	{
 		if (!empty($data)) {
	 		$news_ids = explode("\r\n", $data);
			if (!empty($news_ids)) {
				$tmp_str = array();
				foreach ($news_ids as $news_val) {
					if(!empty($news_val)) $tmp_str[] = "(".$topic_id.",".$news_val.")";
				}
				$in_str = implode(",", $tmp_str);
				return $this->dbstuff->Execute("REPLACE INTO {$this->table_prefix}topicnews VALUES {$in_str}");
			}else{
				return false;
			}
 		}else{
 			return false;
 		}
 	}
}
?>