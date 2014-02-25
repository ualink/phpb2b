<?php
class Productprices extends PbModel {
 	
 	var $name = "Productprice";

 	function __construct()
 	{
 		parent::__construct();
 	}
 	
 	function initSearch()
 	{
 		if (isset($_GET['catid'])) {
 			$cat_id = intval($_GET['catid']);
 		}
 		if (isset($_GET['typeid'])) {
 			$type_id = intval($_GET['typeid']);
 		}
 		if (isset($_GET['areaid'])) {
 			$area_id = intval($_GET['areaid']);
 		}
 		if($type_id){
 			$this->condition[] = "Productprice.type_id=".$type_id;
 		}
 		if($cat_id){
 			$this->condition[] = "Productprice.category_id=".$cat_id;
 		}
 		if($area_id){
 			$this->condition[] = "Productprice.area_id=".$area_id;
 		}
 		$this->amount = $this->findCount();
 	}
 	
 	function Search($firstcount, $displaypg)
 	{
 		global $cache_types;
 		$result = $this->findAll("*,description AS digest", null, null, "id DESC", $firstcount, $displaypg);
 		while(list($keys,$values) = each($result)){
 			$result[$keys]['pubdate'] = df($values['created']);
 			$result[$keys]['url'] = $this->getPermaLink($values['id'], "product/price");
 		}
 		return $result;
 	}
 	
 	function getInfo($id)
	{
		$sql = "SELECT pp.*,m.username,c.name AS companyname,p.name AS productname,b.name AS brandname FROM {$this->table_prefix}productprices pp LEFT JOIN {$this->table_prefix}members m ON m.id=pp.member_id LEFT JOIN {$this->table_prefix}companies c ON c.member_id=pp.member_id LEFT JOIN {$this->table_prefix}products p ON p.id=pp.product_id LEFT JOIN  {$this->table_prefix}brands b ON b.id=pp.brand_id  WHERE pp.id=".$id;
		$result = $this->dbstuff->GetRow($sql);
		return $result;
	}
}
?>