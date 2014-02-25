<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
uses("helptype");
require(LIB_PATH. 'page.class.php');
require("session_cp.inc.php");
$helptype = new Helptypes();
$page = new Pages();
$tpl_file = "helptype";
$conditions = array();
if (isset($_POST['del']) && is_array($_POST['id'])) {
	$deleted = $helptype->del($_POST['id']);
	if (!$deleted) {
		flash();
	}
}
if (isset($_GET['do'])) {
	$do = trim($_GET['do']);
	if ($do =="search" && !empty($_GET['q'])) {
		$conditions[] = "title like '%".trim($_GET['q'])."%'";
	}
	if ($do == "del" && !empty($_GET['id'])) {
		$helptype->del($_GET['id']);
	}
	if($do == "edit"){
		setvar("HelptypeOptions", $helptype->getTypeOptions());
		if(isset($_GET['id'])){
			$id = intval($_GET['id']);
			$res= $helptype->read("*",$id);
			setvar("HelptypeOptions", $helptype->getTypeOptions($res['parent_id']));
			setvar("item",$res);
		}
		$tpl_file = "helptype.edit";
		template($tpl_file);
		exit;
	}
}
if (isset($_POST['save'])) {
	$vals = array();
	$vals = $_POST['data']['helptype'];
	//╦Ы╬щparent_idеп╤оlevel
	if (empty($vals['parent_id']) || (!$vals['parent_id'])) {
		$vals['level'] = 1;
	}else{
		$vals['level'] = $pdb->GetOne("SELECT level+1 FROM {$tb_prefix}helptypes WHERE id=".$vals['parent_id']);
	}
	if (!empty($_POST['id'])) {
		$result = $helptype->save($vals, "update", $_POST['id']);
	}else{
		$result = $helptype->save($vals);
	}
	if (!$result) {
		flash();
	}
}
if (isset($_GET['q'])) {
	$conditions[] = "name like '%".$_GET['q']."%'";
}
$amount = $helptype->findCount(null, $conditions);
$page->setPagenav($amount);
setvar("Items", $helptype->findAll("*", null, $conditions, "id DESC", $page->firstcount, $page->displaypg));
setvar("ByPages",$page->pagenav);
template($tpl_file);
?>