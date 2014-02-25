<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
uses("brandtype", "typeoption");
require(LIB_PATH. 'page.class.php');
require(LIB_PATH. 'cache.class.php');
require("session_cp.inc.php");
$brandtypes = new Brandtypes();
$typeoption = new Typeoption();
$type_models = new Typeoptions();
$cache = new Caches();
$page = new Pages();
$conditions = array();
$tpl_file = "brandtype";
setvar("AskAction", $typeoption->get_cache_type("common_option"));
if (isset($_POST['save']) && !empty($_POST['data'])) {
	if ($_POST['data']['method'] == 2) {
		$result = $type_models->copy("brandtypes", $_POST['data']['truncate'], $_POST['data']['coverage']);
	}else{
		$vals = array();
		$vals = $_POST['data']['brandtype'];
		$vals['level'] = intval($pdb->GetOne("SELECT level AS new_level FROM {$tb_prefix}brandtypes WHERE id='".$vals['parent_id']."'")+1);
		if (!empty($_POST['id'])) {
			$result = $brandtypes->save($vals, "update", $_POST['id']);
		}elseif (!empty($vals['name'])){
			$names = explode("\r\n", $vals['name']);
			$tmp_name = array();
			if (!empty($names)) {
				foreach ($names as $val) {
					$name = $val;
					if(!empty($name)) $tmp_name[] = "('".$name."','".$vals['level']."','".$vals['parent_id']."')";
				}
				$values = implode(",", $tmp_name);
				$sql = "INSERT INTO {$tb_prefix}brandtypes (name,level,parent_id) VALUES ".$values;
				$result = $pdb->Execute($sql);
			}
		}
	}
	if (!$result) {
		flash();
	}
}
if(isset($_POST['del'])&&!empty($_POST['id'])){
	$deleted = $brandtypes->del($_POST['id']);
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
		setvar("BrandtypeOptions", $brandtypes->getTypeOptions());
		if(!empty($id)){
			$res= $brandtypes->read("*",$id);
			setvar("item",$res);
		}
		$tpl_file = "brandtype.edit";
		template($tpl_file);
		exit;
	}
}
if (isset($_GET['q'])) {
	$conditions[] = "name like '%".$_GET['q']."%'";
}
$amount = $brandtypes->findCount(null, $conditions);
$page->setPagenav($amount);
$brandtype_list = $brandtypes->findAll("*", null, $conditions, "id DESC", $page->firstcount, $page->displaypg);
setvar("Items",$brandtype_list);
uaAssign(array("ByPages"=>$page->pagenav));
template($tpl_file);