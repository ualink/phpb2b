<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
uses("quote", "quotetype");
require(LIB_PATH. 'page.class.php');
require(LIB_PATH. 'cache.class.php');
require("session_cp.inc.php");
include(CACHE_COMMON_PATH. "cache_typeoption.php");
$marketquotes = new Quotes();
$quotetype = new Quotetypes();
$cache = new Caches();
$page = new Pages();
$conditions = array();
setvar("Measuries", $_PB_CACHE['measuring']);
setvar("Monetaries", $_PB_CACHE['monetary']);
$tpl_file = "quote";
if (isset($_GET['do'])) {
	$do = trim($_GET['do']);
	if (!empty($_GET['id'])) {
		$id = intval($_GET['id']);
	}
	if ($do == "search") {
		if (isset($_GET['q'])) $conditions[]= "title like '%".trim($_GET['q'])."%'";
	}
	if ($do == "del" && !empty($id)) {
		$marketquotes->del($_GET['id']);
	}
	if ($do == "edit") {
		if(!empty($id)){
			$item_info = $marketquotes->getInfo($id);
		}
		if (!empty($item_info)) {
			$tmp = unserialize($item_info['trend_data']);
			$item_info['trend_x'] = PbController::convertTextToArray($tmp['x'],"DECODE");
			$item_info['trend_y'] = PbController::convertTextToArray($tmp['y'],"DECODE");
			setvar("item",$item_info);
		}
		setvar("typeOptions", $quotetype->getTypeOptions());
		$tpl_file = "quote.edit";
		template($tpl_file);
		exit;
	}
}
if (isset($_POST['del']) && is_array($_POST['id'])) {
	$deleted = $marketquotes->del($_POST['id']);
	if (!$deleted) {
		flash();
	}
}
if (isset($_POST['save']) && !empty($_POST['data'])) {
	if (isset($_POST['id'])) {
		$id = intval($_POST['id']);
	}
	$vals = array();
	$vals = $_POST['data'];
	if (isset($_POST['quote']['market_name'])) {
		if (!pb_strcomp($_POST['quote']['market_name'], $_POST['market_name'])) {
			$vals['market_id'] = $pdb->GetOne("SELECT id FROM {$tb_prefix}markets WHERE name='".$_POST['quote']['market_name']."'");
		}else{
			$vals['market_id'] = $pdb->GetOne("SELECT id FROM {$tb_prefix}markets WHERE name='".$_POST['market_name']."'");
		}
	}
	if (isset($_POST['quote']['product_name'])) {
		if (!pb_strcomp($_POST['quote']['product_name'], $_POST['product_name'])) {
			$vals['product_id'] = $pdb->GetOne("SELECT id FROM {$tb_prefix}products WHERE name='".$_POST['quote']['product_name']."'");
		}else{
			$vals['product_id'] = $pdb->GetOne("SELECT id FROM {$tb_prefix}products WHERE name='".$_POST['product_name']."'");
		}
	}
	$trend['x'] = PbController::convertTextToArray($_POST['trend_data']['x']);
	$trend['y'] = PbController::convertTextToArray($_POST['trend_data']['y']);
	$vals['trend_data'] = serialize($trend);
	$vals['area_id'] = $vals['area_id3'];
	if (!empty($vals['content'])) {
		$vals['content'] = stripcslashes($vals['content']);
	}
	if(!empty($id)){
		$vals['modified'] = $time_stamp;
		$result = $marketquotes->save($vals, "update", $id);
	}else{
		$vals['created'] = $vals['modified'] = $time_stamp;
		$result = $marketquotes->save($vals);
	}
	if (!$result) {
		flash();
	}else{
		flash("success");
	}
}
$amount = $marketquotes->findCount(null, $conditions);
$page->setPagenav($amount);
$joins[] = "LEFT JOIN {$tb_prefix}markets m ON m.id=Quote.market_id";
$fields = "Quote.id,m.name AS marketname,Quote.title AS title,Quote.max_price AS maxprice,Quote.min_price AS minprice";
$result = $marketquotes->findAll($fields, $joins, $conditions,"Quote.id DESC",$page->firstcount,$page->displaypg);
uaAssign(array("ByPages"=>$page->pagenav));
setvar("Items", $result);
template($tpl_file);