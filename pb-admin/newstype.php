<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
uses("newstype");
require(LIB_PATH. 'page.class.php');
require(LIB_PATH. 'cache.class.php');
require("session_cp.inc.php");
$newstype = new Newstypes();
$cache = new Caches();
$page = new Pages();
$conditions = array();
$tpl_file = "newstype";
if (isset($_POST['save']) && !empty($_POST['data']['newstype']['name'])) {
	$vals = array();
	$vals = $_POST['data']['newstype'];
	$vals['level_id'] = intval($pdb->GetOne("SELECT level_id AS new_levelid FROM {$tb_prefix}newstypes WHERE id='".$vals['parent_id']."'")+1);
	if (!empty($_POST['id'])) {
		$result = $newstype->save($vals, "update", $_POST['id']);
	}elseif (!empty($vals['name'])){
		$vals['created'] = $time_stamp;
		$names = explode("\r\n", $vals['name']);
		$tmp_name = array();
		if (!empty($names)) {
			foreach ($names as $val) {
				$name = $val;
				if(!empty($name)) $tmp_name[] = "('".$name."','".$vals['level_id']."','".$vals['parent_id']."','".$vals['created']."')";
			}
			$values = implode(",", $tmp_name);
			$sql = "INSERT INTO {$tb_prefix}newstypes (name,level_id,parent_id,created) VALUES ".$values;
			$result = $pdb->Execute($sql);
		}
	}
	if (!$result) {
		flash();
	}
	$cache->updateTypes();
}
if (isset($_GET['do'])) {
	$do = trim($_GET['do']);
	if (!empty($_GET['id'])) {
		$id = intval($_GET['id']);
	}
	if ($do == 'search') {
		if (isset($_GET['newstype']['name'])) $conditions[]= "Newstype.name like '%".trim($_GET['newstype']['name'])."%'";
	}
	if($do == "del" && !empty($id)){
		$newstype->del($id);
	}
	if ($do == "edit") {
		setvar("NewstypeOptions", $newstype->getTypeOptions());
		if(!empty($id)){
			$res= $newstype->read("*",$id);
			setvar("item",$res);
		}
		$tpl_file = "newstype.edit";
		template($tpl_file);
		exit;
	}
}
$amount = $newstype->findCount(null, $conditions);
$page->setPagenav($amount);
$sql = "SELECT nt.*,(SELECT count(n.id)) AS news_amount FROM ".$tb_prefix."newstypes nt LEFT JOIN ".$tb_prefix."newses n ON n.type_id=nt.id GROUP BY nt.id ORDER BY nt.id DESC LIMIT $page->firstcount,$page->displaypg";
$newstype_list = $pdb->GetArray($sql);
setvar("Items",$newstype_list);
uaAssign(array("ByPages"=>$page->pagenav));
if (isset($_POST['del']) && is_array($_POST['id'])) {
	$deleted = $newstype->del($_POST['id']);
	if (!$deleted) {
		flash();
	}
}
template($tpl_file);
?>