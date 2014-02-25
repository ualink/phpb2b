<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
require(LIB_PATH. 'page.class.php');
require("session_cp.inc.php");
require(LIB_PATH. "cache.class.php");
require(CACHE_COMMON_PATH."cache_type.php");
$_PB_CACHE['area'] = cache_read("area");
uses("area", "typeoption", "country");
$cache = new Caches();
$area = new Areas();
$country = new Countries();
$typeoption = new Typeoption();
$condition = null;
$conditions = array();
$tpl_file = "area";
$page = new Pages();
$cache_items = $_PB_CACHE['area'];
setvar("Types", $_PB_CACHE['areatype']);
setvar("AskAction", $typeoption->get_cache_type("common_option"));
if (isset($_POST['del'])) {
	if (!empty($_POST['id'])) {
		$area->del($_POST['id']);
	}
}
if (isset($_POST['do'])) {
	$do = trim($_POST['do']);
	if ($do == "clear") {
		foreach ($_POST['data']['level'] as $key=>$val){
			$result = $pdb->Execute("DELETE FROM {$tb_prefix}areas WHERE level='".$val."'");
		}
		if(!$result){
			flash();
		}
	}
}
if (isset($_POST['update_batch'])) {
	if (!empty($_POST['data']['aname'])) {
		for($i=0; $i<count($_POST['data']['aname']); $i++) {
			$pdb->Execute("UPDATE {$tb_prefix}areas SET name = '".$_POST['data']['aname'][$i]."' WHERE id='".$_POST['aid'][$i]."'");
		}		
		for($i=0; $i<count($_POST['data']['aname']); $i++) {
			$pdb->Execute("UPDATE {$tb_prefix}areas SET display_order = '".$_POST['data']['display_order'][$i]."' WHERE id='".$_POST['aid'][$i]."'");
		}
	}
	flash("success");
}
if (isset($_POST['del_country']) && !empty($_POST['id'])) {
	$result = $country->del($_POST['id']);
	if (!$result) {
		flash();
	}else{
		$cache->writeCache("country", "country");
		flash("success", "area.php?do=country");
	}
}
if (isset($_POST['update_country'])) {
	if (!empty($_POST['tid'])) {
		$type_count = count($_POST['tid']);
		for($i=0; $i<$type_count; $i++){
			if (!empty($_POST['data']['name'][$i])) {
				$pdb->Execute("UPDATE {$tb_prefix}countries SET name='".$_POST['data']['name'][$i]."',display_order='".$_POST['data']['display_order'][$i]."',picture='".$_POST['data']['picture'][$i]."' WHERE id=".$_POST['tid'][$i]);
			}
		}
	}
	if (!empty($_POST['name'])) {
		$name_count = count($_POST['name']);
		for($j=0; $j<$name_count; $j++){
			if (!empty($_POST['name'][$j])) {
				$pdb->Execute("INSERT INTO {$tb_prefix}countries (name,display_order,picture) values ('".$_POST['name'][$j]."','".$_POST['display_order'][$j]."','".$_POST['picture'][$j]."')");
			}
		}

	}
	$cache->writeCache("country", "country");
	flash("success");
}
if (isset($_POST['save'])) {
	if (isset($_POST['data']['area']['parent_id'])) {
		$parent_id = $_POST['data']['area']['parent_id'];
		if ($parent_id == 0) {
			$top_parentid = $_POST['data']['area']['top_parentid'] = 0;
			$level = $_POST['data']['area']['level'] = 1;
		}else{
			if (array_key_exists($parent_id, $cache_items[1])) {
				$level = $_POST['data']['area']['level'] = 2;
				$top_parentid = $_POST['data']['area']['top_parentid'] = $parent_id;
			}elseif (array_key_exists($parent_id, $cache_items[2])){
				$level = $_POST['data']['area']['level'] = 3;
				$top_parentid = $_POST['data']['area']['top_parentid'] = $pdb->GetOne("SELECT parent_id FROM {$tb_prefix}areas WHERE id=".$parent_id);
			}
		}
	}
	if (isset($_POST['id'])) {
		$id = intval($_POST['id']);
		$vals = $_POST['data']['area'];
		//highlight
		$highlight_style = $_POST['highlight']['style'];//array
		$highlight_color = array_search(strtoupper($_POST['highlight']['color']), $viewhelper->colorarray);
		$stylebin = '';
		for($i = 1; $i <= 3; $i++) {
			$stylebin .= empty($highlight_style[$i]) ? '0' : '1';
		}
		$highlight_style = bindec($stylebin);
		if($highlight_style < 0 || $highlight_style > 7 || $highlight_color < 0 || $highlight_color > 8) {
			;
		}else{
			$highlight_style = $highlight_style.$highlight_color;
			$vals['highlight'] = $highlight_style;
		}//end highlight
//		$vals['description'] = serialize($_POST['data']['lang']);
		$result = $area->save($vals, "update", $id);
	}elseif (!empty($_POST['data']['names'])){
		$names = explode("\r\n", $_POST['data']['names']);
		$tmp_name = array();
		if (!empty($names)) {
			foreach ($names as $val) {
				$name = $val;
				if(!empty($name)) $tmp_name[] = "('".$name."','".$_POST['data']['area']['url']."','".$parent_id."','".$top_parentid."','".$level."','".$_POST['data']['area']['display_order']."','".$_POST['data']['area']['areatype_id']."')";
			}
			$values = implode(",", $tmp_name);
			$sql = "INSERT INTO {$tb_prefix}areas (name,url,parent_id,top_parentid,level,display_order,areatype_id) VALUES ".$values;
			$result = $pdb->Execute($sql);
		}
	}
	if ($result) {
		$cache->writeCache("area", "area");
//		$_languages = unserialize($_PB_CACHE['setting']['languages']);
//		foreach ($_languages as $key=>$val) {
//			$cache->lang_dirname = $key;
//			$cache->cache_path = PHPB2B_ROOT."data".DS."cache".DS.$key.DS;
//		}
	}
}
if (isset($_GET['do'])) {
	$do = trim($_GET['do']);
	if (!empty($_GET['id'])) {
		$id = intval($_GET['id']);
	}
	if ($do == "level") {
		if(!empty($id)){
			if ($_GET['action']=="up") {
				$pdb->Execute("UPDATE {$tb_prefix}areas SET display_order=display_order-1 WHERE id=".$id);
			}elseif ($_GET['action']=="down"){
				$pdb->Execute("UPDATE {$tb_prefix}areas SET display_order=display_order+1 WHERE id=".$id);
			}
		}
	}
	if ($do == "refresh") {
//		$_languages = unserialize($_PB_CACHE['setting']['languages']);
//		foreach ($_languages as $key=>$val) {
//			$cache->lang_dirname = $key;
//			$cache->cache_path = PHPB2B_ROOT."data".DS."cache".DS.$key.DS;
//		}
		$cache->writeCache("area", "area");
		$cache->writeCache("country", "country");
		flash("success");
	}
	if ($do == "search") {
		if (!empty($_GET['name'])) {
			$conditions[] = "name LIKE '%".$_GET['name']."%'";
		}
		if (isset($_GET['parentid'])) {
			$conditions[] = "parent_id=".intval($_GET['parentid']);
		}
		if (isset($_GET['level'])) {
			$conditions[] = "level=".intval($_GET['level']);
		}
		if (isset($_GET['typeid'])) {
			$conditions[] = "areatype_id=".intval($_GET['typeid']);
		}
	}
	if ($do == "edit") {
		setvar("CacheItems", $area->getTypeOptions());
		foreach ($viewhelper->colorarray as $color) {
			$colors[] = '"'.substr($color, 1).'"';
		}
		setvar("colors", implode(",", $colors));
		if (!empty($id)) {
			$res = $pdb->GetRow("SELECT * FROM {$tb_prefix}areas WHERE id=".$id);
			$highlight_style = parse_highlight($res['highlight'], true);
			$res['langs'] = unserialize($res['description']);
			setvar("hl", $highlight_style);
			setvar("item", $res);
		}
		$tpl_file = "area.edit";
		template($tpl_file);
		exit;
	}
	if ($do == "clear") {
		$tpl_file = "area.clear";
		template($tpl_file);
		exit;
	}
	if ($do == "country") {
		$tpl_file = "area.country";
		$result = $country->findAll(null, null, null, "display_order ASC");
		setvar("Items", $result);
		template($tpl_file);
		exit;
	}
}
$amount = $area->findCount(null, $conditions);
$page->setPagenav($amount);
$result = $area->findAll("id,name,name as title,highlight,parent_id,areatype_id,top_parentid,level,display_order", null, $conditions, "level ASC,display_order ASC,id ASC", $page->firstcount, $page->displaypg);
if (!empty($result)) {
	for($i=0; $i<count($result); $i++){
		$tmp_name = array();
		if($result[$i]['level']>1){
			if($result[$i]['level']>2){
				$tmp_name[] = $result[$i]['name'];
				if($_PB_CACHE['area'][2][$result[$i]['parent_id']]) $tmp_name[] = "<a href='area.php?do=search&parentid=".$result[$i]['parent_id']."'>".$_PB_CACHE['area'][2][$result[$i]['parent_id']]."</a>";
				if($_PB_CACHE['area'][1][$result[$i]['top_parentid']]) $tmp_name[] = "<a href='area.php?do=search&parentid=".$result[$i]['top_parentid']."'>".$_PB_CACHE['area'][1][$result[$i]['top_parentid']]."</a>";
			}else{
				$tmp_name[] = "<a href='area.php?do=search&parentid=".$result[$i]['id']."'>".$result[$i]['name']."</a>";
				if($_PB_CACHE['area'][1][$result[$i]['parent_id']]) $tmp_name[] = "<a href='area.php?do=search&parentid=".$result[$i]['parent_id']."'>".$_PB_CACHE['area'][1][$result[$i]['parent_id']]."</a>";
			}
		}else{
			$tmp_name[] = "<a href='area.php?do=search&parentid=".$result[$i]['id']."'>".$result[$i]['name']."</a>";
		}
		$result[$i]['title'] = implode("&laquo;", $tmp_name);
	}
	setvar("Items", $result);
	setvar("ByPages", $page->pagenav);
}
$stats = $pdb->GetArray("SELECT level,count(id) as amount FROM ".$tb_prefix."areas GROUP BY level");
setvar("LevelStats", $stats);
template($tpl_file);
?>