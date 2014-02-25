<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
uses("productcategory", "typeoption");
require(LIB_PATH. 'page.class.php');
require(LIB_PATH. 'cache.class.php');
require("session_cp.inc.php");
$productcategories = new Productcategories();
$cache = new Caches();
$page = new Pages();
$typeoption = new Typeoption();
$type_models = new Typeoptions();
$conditions = array();
$tpl_file = "productcategory";
setvar("AskAction", $typeoption->get_cache_type("common_option"));
if (isset($_POST['save']) && !empty($_POST['data'])) {
	if ($_POST['data']['method'] == 2) {
		$result = $type_models->copy("productcategories", $_POST['data']['truncate'], $_POST['data']['coverage']);
	}else{
		$vals = array();
		$vals = $_POST['data']['productcategory'];
		$vals['level'] = intval($pdb->GetOne("SELECT level AS new_level FROM {$tb_prefix}productcategories WHERE id='".$vals['parent_id']."'")+1);
		if (!empty($_POST['id'])) {
			$result = $productcategories->save($vals, "update", $_POST['id']);
		}elseif (!empty($vals['name'])){
			$names = explode("\r\n", $vals['name']);
			$tmp_name = array();
			if (!empty($names)) {
				foreach ($names as $val) {
					$name = $val;
					if(!empty($name)) $tmp_name[] = "('".$name."','".$vals['level']."','".$vals['parent_id']."')";
				}
				$values = implode(",", $tmp_name);
				$sql = "INSERT INTO {$tb_prefix}productcategories (name,level,parent_id) VALUES ".$values;
				$result = $pdb->Execute($sql);
			}
		}
	}
	if (!$result) {
		flash();
	}else{
		$cache->updateTypes();
		flash("success");
	}
}
if (isset($_GET['do'])) {
	$do = trim($_GET['do']);
	if (!empty($_GET['id'])) {
		$id = intval($_GET['id']);
	}
	if($do == "del" && !empty($id)){
		$productcategories->del($id);
	}
	if ($do == "search") {
		if (isset($_GET['q'])) $conditions[]= "name like '%".trim($_GET['q'])."%'";
	}
	if($do == "update"){
		$flag = $cache->updateTypes();
		if(!$flag){
			flash();
		}else{
			flash("success");
		}
	}
	if ($do == "edit") {
		setvar("ProductcategoryOptions", $productcategories->getTypeOptions());
		if(!empty($id)){
			$res= $productcategories->read("*",$id);
			setvar("item",$res);
		}
		$tpl_file = "productcategory.edit";
		template($tpl_file);
		exit;
	}
}
$amount = $productcategories->findCount(null, $conditions);
$page->setPagenav($amount);
$productcategory_list = $productcategories->findAll("*", null, $conditions, "id DESC", $page->firstcount, $page->displaypg);
setvar("Items",$productcategory_list);
uaAssign(array("ByPages"=>$page->pagenav));
if (isset($_POST['del']) && is_array($_POST['id'])) {
	$deleted = $productcategories->del($_POST['id']);
	if (!$deleted) {
		flash();
	}
}
template($tpl_file);
?>