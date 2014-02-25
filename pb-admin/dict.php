<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
require(PHPB2B_ROOT.'libraries/page.class.php');
require("session_cp.inc.php");
uses("dicttype","dict");
$dict = new Dicts();
$page = new Pages();
$dicttype = new Dicttypes();
$tpl_file = "dict";
if (isset($_GET['do'])) {
	$do = trim($_GET['do']);
	if (!empty($_GET['id'])) {
		$id = intval($_GET['id']);
	}
	if ($do == "search") {
		if(!empty($_GET['help']['title'])) {
			$search_title = $_GET['help']['title'];
			$conditions = "word like '%".$search_title."%'";
		}
	}
	if ($do == "del" && !empty($id)){
		$deleted = false;
		$deleted = $dict->del($id);
	}
	if ($do == "edit") {
		if(!empty($id)){
			$item = $dict->read("*", $id);
			setvar("item", $item);
		}
		if(!empty($item['dicttype_id']))
		setvar("dicttypeOptions", $dicttype_option = $dicttype->getTypeOptions($item['dicttype_id']));
		else
		setvar("dicttypeOptions", $dicttype_option = $dicttype->getTypeOptions());
		$tpl_file = "dict.edit";
		template($tpl_file);
		exit;
	}
}
if(isset($_POST['save']) && !empty($_POST['data']['dict'])){
	$_POST['data']['dict']['word'] = pb_lang_merge($_POST['data']['multi']);
	$vals = $_POST['data']['dict'];
	if(isset($_POST['id'])){
		$id = intval($_POST['id']);
	}
	if(!empty($id)){
		$vals['modified'] = $time_stamp;
		$result = $dict->save($vals, "update", $id);
	}else{
		$vals['created'] = $vals['modified'] = $time_stamp;
		$result = $dict->save($vals);
	}
	if (!$result) {
		flash();
	}
}
if (isset($_GET['q'])) {
	$conditions[] = "word like '%".$_GET['q']."%'";
}
if (isset($_REQUEST['del']) && !empty($_REQUEST['id'])){
	$deleted = false;
	$deleted = $dict->del($_REQUEST['id']);
}
$amount = $dict->findCount(null, $conditions);
$page->setPagenav($amount);
$result = $dict->findAll("Dict.*,t.name AS typename", array("LEFT JOIN {$tb_prefix}dicttypes t ON Dict.dicttype_id=t.id"), $conditions, "Dict.id DESC", $page->firstcount, $page->displaypg);
setvar("Items", $result);
setvar("ByPages", $page->pagenav);
template($tpl_file);
?>