<?php
class Dicts extends PbModel {
 	var $name = "Dict";
 	var $info;
 	var $amount;

 	function __construct()
 	{
 		parent::__construct();
 	}
 	
 	function initSearch()
 	{
 		if (isset($_GET['typeid'])) {
 			$type_id = intval($_GET['typeid']);
 			$this->condition[] = "Dict.dicttype_id='".$type_id."'";
 		}
 		if (!empty($_GET['q'])) {
 			$this->condition[] = "CONCAT(word,content) like '%".$_GET['q']."%'";
 		}
 		if (!empty($_GET['total_count'])) {
 			$this->amount = intval($_GET['total_count']);
 		}else{
 			$this->amount = $this->findCount();
 		}
 		if (!empty($_GET['orderby'])) {
 			switch ($_GET['orderby']) {
 				case "dateline":
 					$this->orderby = "Dict.created DESC";
 					break;
 				default:
 					break;
 			}
 		}
 	}
 	
 	function Search($firstcount, $displaypg)
 	{
 		global $cache_types;
 		$result = $this->findAll("Dict.*,Dict.word as title", null, null, $this->orderby, $firstcount, $displaypg);
 		while(list($keys,$values) = each($result)){
 			$result[$keys]['pubdate'] = df($values['created']);
 			$result[$keys]['typename'] = $cache_types['dicttype'][$values['dicttype_id']];
 			$result[$keys]['url'] = $this->url(array("do"=>"dict", "id"=>$values['id']));
 		}
 		return $result;
 	}
 	
 	function getInfo($id, $name = null)
 	{
 		if (!empty($name)) {
 			$result = $this->dbstuff->GetRow("SELECT d.*,dp.name as typename FROM {$this->table_prefix}dicts d LEFT JOIN {$this->table_prefix}dicttypes dp ON d.dicttype_id=dp.id WHERE d.word='".$name."'");
 		}elseif(!empty($id)){
 			$result = $this->dbstuff->GetRow("SELECT d.*,dp.name as typename FROM {$this->table_prefix}dicts d LEFT JOIN {$this->table_prefix}dicttypes dp ON d.dicttype_id=dp.id WHERE d.id='".$id."'");
 		}else{
 			return false;
 		}
 		return $result;
 	}
}
?>