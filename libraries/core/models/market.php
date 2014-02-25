<?php
class Markets extends PbModel {
 	var $name = "Market";
 	var $amount;

 	function __construct()
 	{
 		parent::__construct();
 	}
 	
 	function initSearch()
 	{
 		$this->condition[] = "status='1'";
 		if(!empty($_GET['q'])) {
 			$this->condition[] = "name like '%".trim($_GET['q'])."%'";
 		}
 		if(!empty($_GET['areaid'])) {
 			$this->condition[] = "area_id = ".intval($_GET['areaid']);
 		}
 		if(!empty($_GET['industryid'])) {
 			$this->condition[] = "industry_id = ".intval($_GET['industryid']);
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
 		$result = $this->findAll("*,Market.name AS title", null, null, $this->orderby, $firstcount, $displaypg);
 		while(list($keys,$values) = each($result)){
 			$result[$keys]['digest'] = $values['content'];
 			$result[$keys]['pubdate'] = df($values['created']);
 			if (isset($cache_types['markettype'][$values['markettype_id']])) {
 				$result[$keys]['typename'] = $cache_types['markettype'][$values['markettype_id']];
 			}
 			$result[$keys]['url'] = $this->url(array("do"=>"market", "id"=>$values['id']));
 			$result[$keys]['thumb'] = pb_get_attachmenturl($values['picture']);
 		}
 		return $result;
 	}
 	
 	function Add()
 	{
 		global $_PB_CACHE;
 		if (isset($this->params['data']['market']['name'])) {
 			$this->params['data']['market']['created'] = $this->params['data']['market']['modified'] = $this->timestamp;
 			$this->params['data']['market']['ip_address'] = pb_get_client_ip('str');
 			$this->params['data']['market']['status'] = 0;
 			return $this->save($this->params['data']['market']);
 		}
 		return false;
 	}
}
?>