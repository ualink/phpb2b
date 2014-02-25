<?php
class Newses extends PbModel {
 	var $name = "News";
 	var $amount;
 	
 	function __construct()
 	{
 		parent::__construct();
 	}
 	
 	function initSearch()
 	{
 		if(isset($_GET['q']) && !empty($_GET['q'])){
 			$title = trim($_GET['q']);
 			$this->condition[] = "CONCAT(title,content) like '%".$title."%'";
 		}
 		if(isset($_GET['typeid'])){
 			$newstype_id = intval($_GET['typeid']);
 			if (!empty($newstype_id)) {
 				$this->condition[] = "type_id=".$newstype_id;
 			}
 		}
 		if (isset($_GET['filter'])) {
 			$filter = intval($_GET['filter']);
 			$this->condition[] = "created>".($this->timestamp-$filter);
 		}
 		if (isset($_GET['topicid'])) {
 			$topic_id = intval($_GET['topicid']);
 			$topic_res = $this->GetRow("SELECT * FROM {$this->table_prefix}topics WHERE id=".$topic_id);
 		}
 		if (!empty($_GET['total_count'])) {
 			$this->amount = intval($_GET['total_count']);
 		}else{
 			$this->amount = $this->findCount();
 		}
 	}
 	
 	function Search($firstcount, $displaypg)
 	{
 		global $cache_types;
 		if (empty($_GET['page'])) {
 			//array_unshift($this->condition,"id between 1000 and 2000");
 		}
 		$orderby = "id DESC";
 		if (isset($_GET['type'])) {
 			$type = trim($_GET['type']);
 			if ($type == "hot") {
 				$orderby = "News.clicked DESC";
 			}
 		}
 		$result = $this->findAll("*,content AS digest", null, null, $orderby, $firstcount, $displaypg);
 		while(list($keys,$values) = each($result)){
 			$result[$keys]['pubdate'] = df($values['created']);
 			$result[$keys]['typename'] = $cache_types['newstype'][$values['type_id']];
 			$result[$keys]['thumb'] =  pb_get_attachmenturl($values['picture'], '', 'small');
 			$result[$keys]['url'] = $this->url(array("do"=>"news", "id"=>$values['id']));
 		}
 		return $result;
 	}
}
?>