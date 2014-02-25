<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
uses("brand","attachment","brandtype","typeoption");
require(LIB_PATH. 'page.class.php');
require(LIB_PATH. 'cache.class.php');
require("session_cp.inc.php");
$attachment = new Attachment('pic');
$brandtypes = new Brandtypes();
$typeoption = new Typeoption();
$brand = new Brands();
$cache = new Caches();
$page = new Pages();
$conditions = array();
$tpl_file = "brand";
setvar("AskAction", $typeoption->get_cache_type("common_option"));
if (isset($_GET['do'])) {
	$do = trim($_GET['do']);
	$action = trim($_GET['action']);
	if (!empty($_GET['id'])) {
		$id = intval($_GET['id']);
	}
	if ($do == "search") {
		if (isset($_GET['q'])) $conditions[]= "Brand.name like '%".trim($_GET['q'])."%'";
	}
	if ($do == "del" && !empty($id)) {
		$sql = "SELECT picture FROM {$tb_prefix}brands WHERE id=".$id;
		$attach_filename = $pdb->GetOne($sql);
		$brand->del($_GET['id']);
		$attachment->deleteBySource($attach_filename);
	}
	if ($do == "edit") {
		$brand_info = null;
		if(!empty($id)){
			$item_info = $brand->getInfo($id);
			if(($item_info['picture'])) $item_info['image'] = pb_get_attachmenturl($item_info['picture'], "../", 'small');
		}
		if (!empty($item_info)) {
			setvar("item",$item_info);
		}
		setvar("BrandtypeOptions", $brandtypes->getTypeOptions());
		$tpl_file = "brand.edit";
		template($tpl_file);
		exit;
	}
}
if (isset($_POST['del']) && is_array($_POST['id'])) {
	foreach ($_POST['id'] as $key=>$val){
		$attach_filename = $pdb->GetOne("select picture from {$tb_prefix}brands where id=".$val);
		$attachment->deleteBySource($attach_filename);
	}
	$deleted = $brand->del($_POST['id']);
	if (!$deleted) {
		flash();
	}
	$cache->updateIndexCache();
}
if (isset($_POST['recommend'])) {
	foreach($_POST['id'] as $val){
		$commend_now = $brand->field("if_commend", "id=".$val);
		if($commend_now=="0"){
			$result = $brand->saveField("if_commend", "1", intval($val));
		}else{
			$result = $brand->saveField("if_commend", "0", intval($val));
		}
	}
	if ($result) {
		flash("success");
	}else{
		flash();
	}
}
if (isset($_POST['save']) && !empty($_POST['data'])) {
	if (isset($_POST['id'])) {
		$id = intval($_POST['id']);
	}
	$vals = array();
	$vals = $_POST['data'];
	if (isset($_POST['brand']['company_name'])) {
		if (!pb_strcomp($_POST['brand']['company_name'], $_POST['company_name'])) {
			$vals['company_id'] = $pdb->GetOne("SELECT id FROM {$tb_prefix}companies WHERE name='".$_POST['brand']['company_name']."'");
		}else{
			$vals['company_id'] = $pdb->GetOne("SELECT id FROM {$tb_prefix}companies WHERE name='".$_POST['company_name']."'");
		}
	}
	if (isset($_POST['brand']['username'])) {
		if (!pb_strcomp($_POST['brand']['username'], $_POST['username'])) {
			$vals['member_id'] = $pdb->GetOne("SELECT id FROM {$tb_prefix}members WHERE username='".$_POST['brand']['username']."'");
		}else{
			$vals['member_id'] = $pdb->GetOne("SELECT id FROM {$tb_prefix}members WHERE username='".$_POST['username']."'");
		}
	}
	$attachment->rename_file = "brand-".($brand->getMaxId()+1);
	if(!empty($id)){
		$attachment->rename_file = "brand-".$id;
	}
	$vals['letter'] = L10n::getinitial($vals['name']);
	if (!empty($vals['description'])) {
		$vals['description'] = stripcslashes($vals['description']);
	}
	if (!empty($_FILES['pic']['name'])) {
		$attachment->upload_process();
		$vals['picture'] = $attachment->file_full_url;
	}
	if(!empty($id)){
		$result = $brand->save($vals, "update", $id);
	}else{
		$result = $brand->save($vals);
	}
	if (!$result) {
		flash();
	}
	$cache->updateIndexCache();
}
if (isset($_GET['q'])) {
	$conditions[] = "Brand.name like '%".$_GET['q']."%'";
}
setvar("Brandtypes", $_PB_CACHE['brandtype']);
$amount = $brand->findCount(null, $conditions);
$joins[] = "LEFT JOIN {$tb_prefix}companies c ON c.id=Brand.company_id";
$page->setPagenav($amount);
$fields = "Brand.id,c.name AS companyname,Brand.name AS name,Brand.alias_name AS aliasname,Brand.type_id AS type_id,Brand.if_commend";
$result = $brand->findAll($fields, $joins, $conditions,"Brand.id DESC",$page->firstcount,$page->displaypg);
uaAssign(array("ByPages"=>$page->pagenav));
setvar("Items", $result);
template($tpl_file);