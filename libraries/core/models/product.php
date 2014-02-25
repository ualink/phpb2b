<?php
class Products extends PbModel {
 	var $name = "Product";
 	var $amount = 0;
 	var $info;
 	var $industry;
 	var $area;

 	function __construct()
 	{
		parent::__construct();
 	}
 	
 	function initSearch()
 	{
 		uses("industry","area");
 		$this->area = new Areas();
 		$this->industry = new Industries();
 		$this->condition[] = "Product.status=1 ";
 		if (isset($_GET['industryid'])) {
 			if (strpos($_GET['industryid'], ",")!==false) {
 				$this->condition[]= "Product.industry_id IN (".trim($_GET['industryid']).")";
 			}else{
	 			$industryid = intval($_GET['industryid']);
	 			$sub_ids = $this->industry->getSubDatas($industryid);
	 			$sub_ids = array_keys($sub_ids);
	 			$sub_ids = array_filter($sub_ids);
	 			$this->condition[]= "Product.industry_id IN (".implode(",", $sub_ids).")";
 			}
 		}
 		if (isset($_GET['areaid'])) {
 			if (strpos($_GET['areaid'], ",")!==false) {
 				$this->condition[]= "Product.area_id IN (".trim($_GET['areaid']).")";
 			}else{
	 			$areaid = intval($_GET['areaid']);
	 			$this->condition[]= "Product.area_id='".$areaid."'";
 			}
 		}
 		if (isset($_GET['type'])) {
 			if($_GET['type']=="commend"){
 				$this->condition[] = "Product.if_commend='1'";
 			}
 		}
 		if (!empty($_GET['typeid'])) {
 			$this->condition[] = "Product.sort_id='".$_GET['typeid']."'";
 		}
 		if(!empty($_GET['q'])) {
 			$searchkeywords = strip_tags($_GET['q']);
 			$this->condition[]= "Product.name like '%".$searchkeywords."%'";
 		}
 		if (isset($_GET['pubdate'])) {
 			switch ($_GET['pubdate']) {
 				case "l3":
 					$this->condition[] = "Product.created>".($this->timestamp-3*86400);
 					break;
 				case "l10":
 					$this->condition[] = "Product.created>".($this->timestamp-10*86400);
 					break;
 				case "l30":
 					$this->condition[] = "Product.created>".($this->timestamp-30*86400);
 					break;
 				default:
 					break;
 			}
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
 		uses("space");
 		$space = new Space();
 		$cache_options = cache_read('typeoption');
 		$area_s = $space->array_multi2single($this->area->getCacheArea());
 		$industry_s = $space->array_multi2single($this->area->getCacheArea());
 		$result = $this->findAll("*,name AS title,content AS digest", null, null, $this->orderby, $firstcount, $displaypg);
 		while(list($keys,$values) = each($result)){
 			$result[$keys]['typename'] = $cache_types['productsort'][$values['sort_id']];
 			$result[$keys]['thumb'] = $result[$keys]['image'] = $result[$keys]['picture'] = pb_get_attachmenturl($values['picture'], '', 'small');
 			if(isset($values['created'])) $result[$keys]['pubdate'] = df($values['created']);
 			else $result[$keys]['pubdate'] = '';
 			if (!empty($result[$keys]['area_id'])) {
 				$r1 = $area_s[$result[$keys]['area_id']];
 			}
 			if (!empty($result[$keys]['cache_companyname'])) {
 				$r2 = "<a href='".$space->rewrite($result[$keys]['cache_companyname'],$result[$keys]['company_id'])."'>".$result[$keys]['cache_companyname']."</a>";
 			}
 			if (!empty($r1) || !empty($r2)) {
 				$result[$keys]['extra'] = implode(" - ", array($r1, $r2));
 			}
 			$result[$keys]['url'] = $this->url(array("do"=>"product", "id"=>$values['id']));
 		}
 		return $result;
 	}

	function checkProducts($id = null, $status = null)
	{
		if(is_array($id)){
			$checkId = "id IN (".implode(",",$id).")";
		}else {
			$checkId = "id=".$id;
		}
		$sql = "UPDATE ".$this->getTable()." SET status=".$status." WHERE ".$checkId;
		$return = $this->dbstuff->Execute($sql);
		if($return){
			return true;
		}else {
			return false;
		}
	}
	
	function getInfo($id)
	{
		$sql = "SELECT p.*,m.username,c.name AS companyname FROM {$this->table_prefix}products p LEFT JOIN {$this->table_prefix}members m ON m.id=p.member_id LEFT JOIN {$this->table_prefix}companies c ON c.member_id=p.member_id WHERE p.id=".$id;
		$result = $this->dbstuff->GetRow($sql);
		return $result;
	}
	
	function getSimilarByMemberId($member_id)
	{
		return $this->findAll("id,name", null, "Product.state=1 AND Product.status=1 AND Product.member_id={$member_id}", "Product.id DESC",0,8);
	}
	
	function getProductById($product_id)
	{
		$sql = "SELECT p.* FROM {$this->table_prefix}products p WHERE p.id=".$product_id;
		$result = $this->dbstuff->GetRow($sql);
		if (empty($result) || !$result) {
			return false;
		}
		$result['pubdate'] = df($result['created']);
		if (!empty($result['picture'])) {
			$result['imgsmall'] = "attachment/".$result['picture'].".small.jpg";
			$result['imgmiddle'] = "attachment/".$result['picture'].".middle.jpg";
			$result['image'] = "attachment/".$result['picture'];
			$result['image_url'] = rawurlencode($result['picture']);
		}else{
			$result['image'] = pb_get_attachmenturl('', '', 'middle');
		}
		$this->info = $result;
		return $result;
	}
	
	function formatResult($result)
	{
		global $rewrite_able;
		$G['membergroup'] = cache_read("membergroup");
		$G['trusttype'] = cache_read("trusttype");
		if (!empty($result)) {
			$count = count($result);
			for($i=0; $i<$count; $i++){
				$result[$i]['pubdate'] = df($result[$i]['created']);
				$result[$i]['content'] = strip_tags($result[$i]['content']);
				$result[$i]['url'] = ($rewrite_able)? "product/detail/".$result[$i]['id'].".html":"index.php?do=product&action=detail&id=".$result[$i]['id'];;
				$result[$i]['gradeimg'] = STATICURL.'images/group/'.$G['membergroup'][$result[$i]['membergroup_id']]['avatar'];
				$result[$i]['gradename'] = $G['membergroup'][$result[$i]['membergroup_id']]['name'];
				$result[$i]['image'] = pb_get_attachmenturl($result[$i]['picture']);
				$trusttype_images = null;
				if(!empty($result[$i]['trusttype_ids'])){
					$tmp_trusttype = explode(",", $result[$i]['trusttype_ids']);
					foreach ($tmp_trusttype as $val) {
						$trusttype_images.='<img src="'.$G['trusttype'][$val]['avatar'].'" alt="'.$G['trusttype'][$val]['name'].'" />';
					}
				}
				$result[$i]['trusttype'] = $trusttype_images;
			}
			return $result;
		}else{
			return null;
		}
	}
}
?>