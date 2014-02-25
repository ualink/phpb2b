<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
uses("expotype");
require("session_cp.inc.php");
require(LIB_PATH. "cache.class.php");
$cache = new Caches();
$expotype = new Expotypes();
$conditions = null;
$tpl_file = "fairtype";
if (isset($_POST['del']) && !empty($_POST['id'])){
	$deleted = false;
	$result = $expotype->del($_POST['id']);
	if(!$result)
	{
		flash();
	}
	$cache->writeCache("expotype", "expotype");
}
if (isset($_GET['do'])) {
	$do = trim($_GET['do']);
	if (!empty($_GET['id'])) {
		$id = intval($_GET['id']);
	}
	if ($do=="del" && $id){
		$deleted = false;
		$result = $expotype->del($id);
		if(!$result)
		{
			flash();
		}
		$cache->writeCache("expotype", "expotype");
	}
	if ($do == "edit") {
		if(!empty($id)){
			$tmp_info = $expotype->read("*",$id);
			setvar("item",$tmp_info);
		}
		$tpl_file = "fairtype.edit";
		template($tpl_file);
		exit;
	}
}
if (isset($_POST['save']) && !empty($_POST['data']['expotype']['name'])) {
	$vals = array();
	$vals = $_POST['data']['expotype'];
	if (isset($_POST['id'])) {
		$id = intval($_POST['id']);
	}
	if(!empty($id)){
		$result = $expotype->save($vals, "update", $id);
	}else{
		$result = $expotype->save($vals);
	}
	if (!$result) {
		flash();
	}
	$cache->updateTypes();
}

$amount = $expotype->findCount();
$fields = "id,name";
$result = $expotype->findAll($fields);
setvar("Items",$result);
template($tpl_file);
?>