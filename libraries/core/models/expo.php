<?php
class Expos extends PbModel {
 	var $name = "Expo";
 	var $info;
 	var $amount;

 	function __construct()
 	{
 		parent::__construct();
 	}
 	
 	function initSearch()
 	{
 		if (!empty($_GET['q'])) {
 			$this->condition[] = "name like '%".$_GET['q']."%'";
 		}
 		if ($_GET['type']=="commend") {
 			$this->condition[] = "if_commend=1";
 		}
 		if(isset($_GET['typeid'])){
 			$type_id = intval($_GET['typeid']);
 			$this->condition[] = "expotype_id=".$type_id;
 		}
 		if (!empty($_GET['total_count'])) {
 			$this->amount = intval($_GET['total_count']);
 		}else{
 			$this->amount = $this->findCount();
 		}
 		if (!empty($_GET['orderby'])) {
 			switch ($_GET['orderby']) {
 				case "dateline":
 					$this->orderby = "created DESC";
 					break;
 				default:
 					break;
 			}
 		}
 	}
 	
 	function Search($firstcount, $displaypg)
 	{
 		global $cache_types;
 		$result = $this->findAll("*,name AS title,description AS digest", null, null, $this->orderby, $firstcount, $displaypg);
 		while(list($keys,$values) = each($result)){
 			$result[$keys]['pubdate'] = df($values['created']);
 			$result[$keys]['begin_date'] = df($values['begin_time']);
 			$result[$keys]['end_date'] = df($values['end_time']);
 			$result[$keys]['typename'] = $cache_types['expotype'][$values['expotype_id']];
 			$result[$keys]['thumb'] = pb_get_attachmenturl($values['picture'], '', 'small');
 			$result[$keys]['url'] = $this->url(array("do"=>"fair", "id"=>$values['id']));
 		}
 		return $result;
 	}
 	
 	function checkExist($id, $extra = false)
 	{
 		$id = intval($id);
 		$info = $this->dbstuff->GetRow("SELECT * FROM {$this->table_prefix}expos WHERE id={$id}");
 		if (empty($info) or !$info) {
 			return false;
 		}else{
 			if ($extra) {
 				$info['begin_date'] = (!$info['begin_time'])?df($this->timestamp):df($info['begin_time']);
 				$info['end_date'] = (!$info['end_time'])?df($this->timestamp):df($info['end_time']);
 				$this->info = $info;
 			}
 			return true;
 		}
 	}
}
?>