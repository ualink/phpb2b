<?php
class Helps extends PbModel {
 	var $name = "Help";

 	function __construct()
 	{
 		parent::__construct();
 	}
 	
 	function initSearch()
 	{
		if (!empty($_GET['q'])) {
			$this->condition[] = "title like '%".trim($_GET['q'])."%'";
		}
		if(isset($_GET['typeid'])) {
			$type_id = intval($_GET['typeid']);
			$this->condition[] = "helptype_id=".$type_id;
		}
 		$this->amount = $this->findCount();
 		if (!empty($_GET['orderby'])) {
 			switch ($_GET['orderby']) {
 				case "dateline":
 					$this->orderby = "Help.created DESC";
 					break;
 				default:
 					break;
 			}
 		}
 	}
 	
 	function Search($firstcount, $displaypg)
 	{
 		global $cache_types;
 		$result = $this->findAll("Help.*,Help.content as digest", null, null, $this->orderby, $firstcount, $displaypg);
 		while(list($keys,$values) = each($result)){
 			$result[$keys]['pubdate'] = df($values['created']);
 			$result[$keys]['typename'] = $cache_types['helptype'][$values['helptype_id']];
 			$result[$keys]['url'] = $this->url(array("do"=>"help", "id"=>$values['id']));
 		}
 		return $result;
 	}
}
?>