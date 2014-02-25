<?php
class Brands extends PbModel {
 	
 	var $name = "Brand";
 	var $amount;

 	function __construct()
 	{
 		parent::__construct();
 	}
 	
 	function initSearch()
 	{
 		if (isset($_GET['q'])) {
 			$searchkeywords = $_GET['q'];
 			$this->condition[] = "name like '%".$searchkeywords."%'";
 		}
 		if (isset($_GET['letter'])) {
 			$this->condition[] = "letter='".trim($_GET['letter'])."'";
 		}
 		$this->amount = $this->findCount();
 	}
 	
 	function Search($firstcount, $displaypg)
 	{
 		global $cache_types;
 		$result = $this->findAll("*,name AS title,description as digest", null, null, "id DESC", $firstcount, $displaypg);
 		while(list($keys,$values) = each($result)){
 			$result[$keys]['pubdate'] = df($values['created']);
 			$result[$keys]['url'] = $this->url(array("do"=>"brand", "id"=>$values['id']));
 			$result[$keys]['thumb'] = pb_get_attachmenturl($values['picture'], '', 'small');
 		}
 		return $result;
 	}

 	function getInfo($id)
	{
		$sql = "SELECT b.*,m.username,c.name AS companyname FROM {$this->table_prefix}brands b LEFT JOIN {$this->table_prefix}members m ON m.id=b.member_id LEFT JOIN {$this->table_prefix}companies c ON c.member_id=b.member_id WHERE b.id=".$id;
		$result = $this->dbstuff->GetRow($sql);
		return $result;
	}
	
	function formatResult($result)
	{
		if(!empty($result)){
			$count = count($result);
			for ($i=0; $i<$count; $i++){
				$result[$i]['pubdate'] = '';
				if(isset($result[$i]['submit_time'])) $result[$i]['pubdate'] = df($result[$i]['submit_time']);
				$result[$i]['image'] = pb_get_attachmenturl($result[$i]['picture'], '', 'small');
			}
			return $result;
		}else{
			return null;
		}
	}
}
?>