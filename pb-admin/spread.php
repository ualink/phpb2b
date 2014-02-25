<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
require("session_cp.inc.php");
require(LIB_PATH .'page.class.php');
uses("spread");
$tpl_file = "spread";
$spread = new Spreads();
$page = new Pages();
$conditions = array();
setvar("CheckStatus", cache_read('typeoption', 'check_status'));
if (isset($_POST['do'])) {
	$do = trim($_POST['do']);
	if ($do == "spread") {
		if (isset($_POST['id'])) {
			$id = intval($_POST['id']);
		}
		$vals = array();
		$vals = $_POST['data']['spread'];
		$vals['expiration'] = 7*86400+$time_stamp;
		if (!empty($_POST['exp_date'])) {
			$vals['expiration'] = strtotime($_POST['exp_date']);
		}
		if(!empty($id)){
			$result = $spread->save($vals, "update", $id);
		}else{
			$vals['created'] = $time_stamp;
			$result = $spread->save($vals);
		}
		if (!$result) {
			flash();
		}else{
			flash('success', 'spread.php');
		}
	}
}
if (isset($_GET['do'])){
	$do = trim($_GET['do']);
	if (!empty($_GET['id'])) {
		$id = intval($_GET['id']);
	}
	if($do=="del" && !empty($id)) {
		$result = $spread->del($_GET['id']);
	}
	if ($do == "edit") {
		if(!empty($id)){
			$item = $spread->read("*", $id);
			if(!empty($item['expiration'])){
				$item['exp_date'] = df($item['expiration']);
			}
			setvar("item", $item);
		}
		$tpl_file = "spread.edit";
		template($tpl_file, true);
	}
}
if (isset($_POST['del']) && !empty($_POST['id'])) {
	$result = $spread->del($_POST['id']);
}
if (isset($_POST['pb_action']) && !empty($_POST['id'])) {
	$status = $_POST['pb_action'];
	$ids = "(".implode(",", $_POST['id']).")";
	$sql = "UPDATE {$tb_prefix}spreads SET status='".$status."' WHERE id IN ".$ids;
	$result = $pdb->Execute($sql);
	if (!$result) {
		flash();
	}
}
$amount = $spread->findCount();
$page->setPagenav($amount);
$result = $spread->findAll("*",null, $conditions, " id desc", $page->firstcount, $page->displaypg);
for($i=0; $i<count($result); $i++){
	$result[$i]['exp_date'] = df($result[$i]['expiration']);
}
setvar("Items",$result);
setvar("ByPages",$page->pagenav);
template($tpl_file);
?>