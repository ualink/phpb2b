<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
uses("tag");
require(PHPB2B_ROOT.'./libraries/page.class.php');
require("session_cp.inc.php");
include(CACHE_COMMON_PATH. "cache_typeoption.php");
$tag = new Tags();
$conditions = null;
$tpl_file = "tag";
$joins = array();
$page = new Pages();
setvar("Status", $_PB_CACHE['common_option']);
if (isset($_GET['do'])) {
	$do = trim($_GET['do']);
	if (!empty($_GET['id'])) {
		$id = intval($_GET['id']);
	}
	if ($do == "edit") {
		if ($id) {
			setvar("item", $tag->read("*", $id));
		}
		$tpl_file = "tag.edit";
		template($tpl_file);
		exit;
	}
	if ($do == "search" && !empty($_GET['q'])) {
		$conditions[]= "Tag.name like '%".trim($_GET['q'])."%'";
	}
	if ($do == "del" && !empty($id)) {
		$tag->del($id);
	}
}
if (isset($_POST['del']) && !empty($_POST['id'])) {
	$tag->del($_POST['id']);
}
if (isset($_POST['save']) && !empty($_POST['data']['tag'])) {
	if (isset($_POST['id'])) {
		$id = intval($_POST['id']);
	}
	if ($id) {
		$tag->save($_POST['data']['tag'], "update", $id);
	}else{
		$tag->save($_POST['data']['tag']);
	}
}
$amount = $tag->findCount(null, $conditions);
$page = new Pages();
$page->setPagenav($amount);
//$joins[] = "LEFT JOIN {$tb_prefix}members m ON m.id=Tag.member_id";
$result = $tag->findAll("Tag.*", $joins, $conditions, "Tag.id DESC ", $page->firstcount, $page->displaypg);
setvar("Items", $result);
setvar("ByPages", $page->getPagenav());
template($tpl_file);
?>