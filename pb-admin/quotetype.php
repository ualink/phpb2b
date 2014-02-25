<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
uses("quotetype", "typeoption");
require(LIB_PATH. 'page.class.php');
require(LIB_PATH. 'cache.class.php');
require("session_cp.inc.php");
$quotetypes = new Quotetypes();
$typeoption = new Typeoption();
$type_models = new Typeoptions();
$cache = new Caches();
$page = new Pages();
$conditions = array();
$tpl_file = "quotetype";
setvar("AskAction", $typeoption->get_cache_type("common_option"));
if (isset($_POST['save']) && !empty($_POST['data'])) {
	if ($_POST['data']['method'] == 2) {
		$result = $type_models->copy("quotetypes", $_POST['data']['truncate'], $_POST['data']['coverage']);
	}else{
		$vals = array();
		$vals = $_POST['data']['quotetype'];
		$vals['level'] = intval($pdb->GetOne("SELECT level AS new_level FROM {$tb_prefix}quotetypes WHERE id='".$vals['parent_id']."'")+1);
		if (!empty($_POST['id'])) {
			$result = $quotetypes->save($vals, "update", $_POST['id']);
		}elseif (!empty($vals['name'])){
			$names = explode("\r\n", $vals['name']);
			$tmp_name = array();
			if (!empty($names)) {
				foreach ($names as $val) {
					$name = $val;
					if(!empty($name)) $tmp_name[] = "('".$name."','".$vals['level']."','".$vals['parent_id']."')";
				}
				$values = implode(",", $tmp_name);
				$sql = "INSERT INTO {$tb_prefix}quotetypes (name,level,parent_id) VALUES ".$values;
				$result = $pdb->Execute($sql);
			}
		}
	}
	if (!$result) {
		flash();
	}
}
if(isset($_POST['del'])&&!empty($_POST['id'])){
	$deleted = $quotetypes->del($_POST['id']);
	if (!$deleted) {
		flash();
	}
}
if (isset($_GET['do'])) {
	$do = trim($_GET['do']);
	if (!empty($_GET['id'])) {
		$id = intval($_GET['id']);
	}
	if ($do == "search") {
		if (isset($_GET['q'])) $conditions[]= "name like '%".trim($_GET['q'])."%'";
	}
	if ($do == "edit") {
		setvar("QuotetypeOptions", $quotetypes->getTypeOptions());
		if(!empty($id)){
			$res= $quotetypes->read("*",$id);
			setvar("item",$res);
		}
		$tpl_file = "quotetype.edit";
		template($tpl_file);
		exit;
	}
}
$amount = $quotetypes->findCount(null, $conditions,"id");
$page->setPagenav($amount);
$brandtype_list = $quotetypes->findAll("*", null, $conditions, "id DESC", $page->firstcount, $page->displaypg);
setvar("Items",$brandtype_list);
uaAssign(array("ByPages"=>$page->pagenav));
template($tpl_file);