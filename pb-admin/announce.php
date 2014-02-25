<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
uses("announcement");
require("session_cp.inc.php");
require(LIB_PATH. "page.class.php");
require(LIB_PATH. "cache.class.php");
require(LIB_PATH. "time.class.php");
require(CACHE_COMMON_PATH."cache_type.php");
$page = new Pages();
$cache = new Caches();
$announce = new Announcements();
$tpl_file = "announce";
setvar("Types", $_PB_CACHE['announcementtype']);
$conditions = array();
if (isset($_POST['del']) && is_array($_POST['id'])) {
	$deleted = $announce->del($_POST['id']);
	if (!$deleted) {
		flash();
	}
}
if (isset($_GET['do'])) {
	$do = trim($_GET['do']);
	if (!empty($_GET['id'])) {
		$id = intval($_GET['id']);
	}
	if($do=="del" && !empty($id)) {
		$announce->del($id);	
	}
	if($do=="edit"){
		if(!empty($id)){
			$res= $announce->read("*",$id);
			$res['display_expiration'] = df($res['display_expiration']);
			setvar("item",$res);
		}
		$tpl_file = "announce.edit";
		template($tpl_file);
		exit;
	}
}
if (isset($_POST['save']) && !empty($_POST['data']['announcement'])) {
	$vals = $_POST['data']['announcement'];
	if(isset($_POST['id'])){
		$id = intval($_POST['id']);
	}
	if (!empty($_POST['data']['display_expiration'])) {
		$vals['display_expiration'] = Times::dateConvert($_POST['data']['display_expiration']);
	}
	if (!empty($id)) {
		$vals['modified'] = $time_stamp;
		$result = $announce->save($vals, "update", $id);
	}else{
		$vals['created'] = $vals['modified'] = $time_stamp;
		$result = $announce->save($vals);
	}
	if (!$result) {
		flash();
	}
}
if (isset($_GET['q'])) {
	$conditions[] = "subject like '%".$_GET['q']."%'";
}
$amount = $announce->findCount(null, $conditions);
$page->setPagenav($amount);
$fields = "id,announcetype_id,announcetype_id as typeid,subject,message,subject AS title,message AS content,created";
setvar("ByPages", $page->pagenav);
$result = $announce->findAll($fields, null, $conditions, "id DESC", $page->firstcount, $page->displaypg);
if (!empty($result)) {
	for($i=0; $i<count($result); $i++){
		if(!empty($result[$i]['created'])) $result[$i]['pubdate'] = df($result[$i]['created']);
	}
	setvar("Items", $result);
}
template($tpl_file);
?>