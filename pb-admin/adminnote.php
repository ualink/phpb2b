<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
require(LIB_PATH .'page.class.php');
require("session_cp.inc.php");
uses("adminnote");
$adminnote = new Adminnotes();
$tpl_file = "adminnote";
$page = new Pages();
$conditions = array("member_id=".$current_adminer_id);
if (isset($_POST['save']) && !empty($_POST['data']['adminnote']['title'])) {
	$vals = $_POST['data']['adminnote'];
	$id = $_POST['id'];
	if (!empty($id)) {
		$vals['modified'] = $time_stamp;
		$result = $adminnote->save($vals, "update", $id);
	}else{
		$vals['member_id'] = $current_adminer_id;
		$vals['created'] = $vals['modified'] = $time_stamp;
		$result = $adminnote->save($vals);
	}
	if (!$result) {
		flash();
	}
}
if (isset($_POST['del']) && !empty($_POST['id'])) {
	$result = $adminnote->del($_POST['id']);
}
if (isset($_GET['do'])){
	$do = trim($_GET['do']);
	if (!empty($_GET['id'])) {
		$id = intval($_GET['id']);
	}
	if($do=="del" && !empty($id)) {
		$result = $adminnote->del($_GET['id'])	;
	}
	if ($do == "edit") {
		if (!empty($id)) {
			$result = $adminnote->read("*", $id);
			setvar("item",$result);
		}
		$tpl_file = "adminnote.edit";
		template($tpl_file);
		exit;
	}
	if ($do == "search") {
		if (!empty($_GET['q'])) {
			$conditions[] = "title like '%".$_GET['q']."%' OR content like '%".$_GET['q']."%'";
		}
	}
}
$amount = $adminnote->findCount();
$page->setPagenav($amount);
$result = $adminnote->findAll("*", null, $conditions, " id desc", $page->firstcount, $page->displaypg);
setvar("Items",$result);
setvar("ByPages",$page->pagenav);
template($tpl_file);
?>