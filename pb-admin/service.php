<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
require(PHPB2B_ROOT.'./libraries/page.class.php');
require("session_cp.inc.php");
require(LIB_PATH. "cache.class.php");
uses("service","typeoption","setting");
$page = new Pages();
$cache = new Caches();
$setting = new Settings();
$typeoption = new Typeoption();
$service = new Services();
$conditions = null;
$tpl_file = "service";
setvar("Status", $typeoption->get_cache_type("common_status"));
setvar("ServiceTypes", $typeoption->get_cache_type("service_type"));

if (isset($_POST['save_client'])) {
	if (!empty($_POST['data']['setting'])) {
		$updated = $setting->replace($_POST['data']['setting'], 1);
		if($updated) {
			$cache->writeCache("setting", "setting");
			flash("success");
		}
	}
	flash();
}
if (isset($_POST['save']) && !empty($_POST['data']['service'])) {
	$vals = array();
	$vals = $_POST['data']['service'];
	$vals['modified'] = $time_stamp;
	$result = $service->save($vals, "update", $_POST['id']);
	if (!empty($vals['revert_content'])) {
		$datas = array(
		"actor"=>$adminer_info['last_name'],
		"action"=> L("feed_revert", "tpl"),
		"do"=> L("feed_problem", "tpl"),
		"subject"=> '<a href="index.php?do=service&action=detail&id='.$_POST['id'].'">'.$vals['title'].'</a>',
		);
		$sql = "INSERT INTO {$tb_prefix}feeds (type_id,type,member_id,username,data,created,modified,revert_date) VALUE ('1','service',".$current_adminer_id.",'".$adminer_info['last_name']."','".serialize($datas)."',".$time_stamp.",".$time_stamp.",".$time_stamp.")";
		$pdb->Execute($sql);
	}
	if (!$result) {
		flash();
	}
}
if (isset($_GET['do'])) {
	$do = trim($_GET['do']);
	if (!empty($_GET['id'])) {
		$id = intval($_GET['id']);
	}
	if ($do == "client") {
		$item = $setting->getValues();
		$tpl_file = "service.client";
		setvar("item", $item);
		template($tpl_file);
		exit;
	}
	if($do=="del" && $id>0){
		$deleted = $service->del($id);
	}
	if ($do == "edit" && !empty($id)) {
		$sql = "SELECT * FROM {$tb_prefix}services WHERE id=".$id;
		$res = $pdb->GetRow($sql);
		if (empty($res)) {
			flash();
		}else {
			setvar("item",$res);
		}
		$tpl_file = "service.edit";
		template($tpl_file);
		exit;
	}
	if ($do == "search") {
		if (!empty($_GET['type_id'])) {
			$conditions[] = "Service.type_id=".$_GET['type_id'];
		}
		if (!empty($_GET['q'])) {
			$conditions[] = "Service.title like '%".$_GET['q']."%' OR Service.content like '%".$_GET['q']."%'";
		}
	}
}
$amount = $service->findCount(null, $conditions,"Service.id");
$page->setPagenav($amount);
setvar("Items",$service->findAll("*", null, $conditions, "Service.id DESC ",$page->firstcount,$page->displaypg));
setvar("ByPages",$page->pagenav);
if (isset($_REQUEST['del'])){
	$deleted = false;
	if(!empty($_POST['id'])) {
		$deleted = $service->del($_POST['id']);
	}
	if(!empty($_GET['id'])){
		$deleted = $service->del($_GET['id']);
	}
	if($deleted) {
		pheader("location:service.php");
	}
	else
	{
		flash();
	}
}
template($tpl_file);
?>