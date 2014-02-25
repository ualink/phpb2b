<?php
class Brand extends PbController {
	var $name = "Brand";
	
	function __construct()
	{
		$this->loadModel("brand");
	}
	
	function index()
	{
		//get cats
		$latest_brands = $this->brand->dbstuff->GetArray("SELECT DISTINCT type_id FROM ".$this->brand->table_prefix."brands");
		if (!empty($latest_brands)) {
			while (list($key, $item) = each($latest_brands)) {
				$sql = "select id,name,picture from ".$this->brand->table_prefix."brands where type_id=".$item['type_id']." order by id desc limit 7";
				$latest_brands[$key]['brands'] = $this->brand->dbstuff->GetArray($sql);
			}
		}
		setvar("LatestBrands", $latest_brands);
		render("brand/index");		
	}
	
	function detail()
	{
		global $viewhelper;
		$condition = null;
		$conditions = array();
		$viewhelper->setPosition(L("brands", "tpl"), "index.php?do=brand");
		if (isset($_GET['name'])) {
			$brand_name = trim($_GET['name']);
			$id = $this->brand->dbstuff->GetOne("SELECT id FROM {$brand->table_prefix}brands WHERE name='".$brand_name."'");
		}
		if (isset($_GET['id'])) {
			$id = intval($_GET['id']);
		}
		if(!empty($id))	{
			$result = $this->brand->dbstuff->GetRow("SELECT * FROM {$this->brand->table_prefix}brands WHERE id='".$id."'");
			if(!empty($result)){
				$title = pb_lang_split($result['name']);
				$result['img'] = pb_get_attachmenturl($result['picture']);
				$result['title'] = $title;
				$viewhelper->setPosition($this->brand->dbstuff->GetOne("SELECT name FROM ".$this->brand->table_prefix."brandtypes WHERE id=".$result['type_id']), "index.php?do=brand&action=lists&catid=".$result['type_id']);
				$viewhelper->setTitle($title);
				$viewhelper->setPosition($title);
			}
		}else{
			L("data_not_exists");
		}
		setvar("item",$result);
		render("brand/detail");		
	}
	
	function lists()
	{
		global $viewhelper, $pos;
		$condition = null;
		$conditions = array();
		$viewhelper->setTitle(L("brands", "tpl"));
		$viewhelper->setPosition(L("brands", "tpl"), "index.php?do=brand");
		if (isset($_GET['catid'])) {
			$typeid = intval($_GET['catid']);
			//$conditions[] = "type_id='".$typeid."'";//after 4.0.1
			$type_info = $this->brand->dbstuff->GetRow("SELECT * FROM {$this->brand->table_prefix}brandtypes WHERE id='".$typeid."'");
			if (!empty($type_info)) {
				$type_ids = $this->brand->dbstuff->GetArray("SELECT id FROM {$this->brand->table_prefix}brandtypes WHERE parent_id='".$typeid."' OR id='".$typeid."'");
				foreach ($type_ids as $key=>$val) {
					$tmp[] = $val['id'];
				}
				if (!empty($tmp)) {
					$conditions[] = "type_id IN (".implode(",", $tmp).")";
				}
			}
			$type_name = pb_lang_split($type_info['name']);
			$viewhelper->setTitle($type_name);
			$viewhelper->setPosition($type_name, "index.php?do=brand&action=lists&catid=".$typeid);
			$rs = $this->brand->dbstuff->GetArray("SELECT id,name FROM {$this->brand->table_prefix}brandtypes WHERE parent_id='".$typeid."'");
		}
		if (empty($rs)) {
			$rs = $this->brand->dbstuff->GetArray("SELECT id,name FROM {$this->brand->table_prefix}brandtypes WHERE parent_id=0");
		}
		if (isset($_GET['q'])) {
			$searchkeywords = $_GET['q'];
			$conditions[] = "name like '%".$searchkeywords."%'";
		}
		if (isset($_GET['letter'])) {
			$viewhelper->setTitle(L("brands_with_letter", "tpl", $_GET['letter']));
			$viewhelper->setPosition(L("brands_with_letter", "tpl", $_GET['letter']));
			$conditions[] = "letter='".trim($_GET['letter'])."'";
		}
		$this->brand->setCondition($conditions);
		$amount = $this->brand->findCount(null, $conditions);
		$sql = "SELECT * FROM {$this->brand->table_prefix}brands".$this->brand->getCondition()." LIMIT ".$pos.",".$this->displaypg;
		$result = $this->brand->dbstuff->GetArray($sql);
		$result = $this->brand->formatResult($result);
		setvar('items', $result);
		setvar('Types', pb_lang_split_recursive($rs));
		setvar("paging", array('total'=>$amount));
		render("brand/list");		
	}
}
?>