<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
require("session_cp.inc.php");
uses("nav", "typeoption");
require(LIB_PATH. "cache.class.php");
$cache = new Caches();
$nav = new Navs();
$typeoption = new Typeoption();
$conditions = null;
$tpl_file = "nav";
setvar("AskAction", $typeoption->get_cache_type("common_option"));
if (isset($_POST['del']) && is_array($_POST['id'])) {
	$deleted = $nav->del($_POST['id']);
	if (!$deleted) {
		flash();
	}
	$cache->writeCache("nav", "nav");
}
if (isset($_POST['update_prior'])) {
	if (!empty($_POST['nid'])) {
		for ($i=0; $i<count($_POST['nid']); $i++){
			$pdb->Execute("UPDATE {$tb_prefix}navs SET display_order='".$_POST['display_order'][$i]."',status='".$_POST['status'][$i]."' WHERE id='".$_POST['nid'][$i]."'");
		}
		$cache->writeCache("nav", "nav");
	}
}
if (isset($_POST['save'])) {
	$vals = array();
	$_POST['data']['nav']['name'] = pb_lang_merge($_POST['data']['multi']);
	$vals = $_POST['data']['nav'];
//	$vals['description'] = serialize($_POST['data']['lang']);
//	if(!empty($_POST['data']['lang'][$app_lang])) $vals['name'] = $_POST['data']['lang'][$app_lang];
	if (!empty($_POST['id'])) {
		$vals['modified'] = $time_stamp;
		$result = $nav->save($vals, "update", $_POST['id']);
	}else{
		$vals['created'] = $vals['modified'] = $time_stamp;
		$result = $nav->save($vals);
	}
	if (!$result) {
		flash();
	}
	$cache->writeCache("nav", "nav");
//	foreach ($_POST['data']['lang'] as $key=>$val) {
//		$cache->lang_dirname = $key;
//		$cache->writeCache("nav", "nav");
//	}
}
if (isset($_GET['do'])) {
	$do = trim($_GET['do']);
	if (!empty($_GET['id'])) {
		$id = intval($_GET['id']);
	}
	if ($do == "edit") {
		if(!empty($id)){
			$res= $nav->read("*",$id);
			$res['langs'] = unserialize($res['description']);
			setvar("item",$res);
		}
		$tpl_file = "nav.edit";
		template($tpl_file);
		exit;
	}
}
$result = $nav->findAll("*", null, $conditions, "display_order ASC,id ASC");
if (!empty($result)) {
	for($i=0; $i<count($result); $i++){
		$result[$i]['title'] = "<a".parse_highlight($result[$i]['highlight']).">".$result[$i]['name']."</a>";
	}
}
setvar("Items", $result);
template($tpl_file);
?>