<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
require("session_cp.inc.php");
uses("standard","standardtype","attachment");
require(LIB_PATH .'time.class.php');
require(PHPB2B_ROOT.'libraries/page.class.php');
require(LIB_PATH. "cache.class.php");
require(CACHE_COMMON_PATH."cache_type.php");
$page = new Pages();
$standard = new Standards();
$attachment = new Attachment('attach');
$attachment_model = new Attachments();
$cache = new Caches();
$standardtype = new Standardtypes();
$conditions = array();
$fields = null;
$tpl_file = "standard";
if (isset($_POST['save']) && !empty($_POST['data']['standard'])) {
	$vals = $_POST['data']['standard'];
	if (isset($_POST['id'])) {
		$id = intval($_POST['id']);
	}
	if(!empty($_POST['data']['publish_time'])){
		$vals['publish_time'] = Times::dateConvert($_POST['data']['publish_time']);
	}
	if(!empty($_POST['data']['force_time'])){
		$vals['force_time'] = Times::dateConvert($_POST['data']['force_time']);
	}
	$allowed_ext = array(".zip", ".rar", ".pdf", ".doc", ".xls", ".txt", ".ppt");
	$attachment->allowed_file_ext = am($attachment->allowed_file_ext, $allowed_ext);
	$attachment->rename_file = "standard-".md5($time_stamp);
	$attachment->description = trim($vals['title']);
	if(!empty($id)){
		$attachment->rename_file = "standard-".md5($id);
	}
	if (!empty($_FILES['attach']['name'])) {
		$attachment->upload_process();
		$vals['attachment_id'] = $attachment->id;
	}
	if(!empty($id)){
		$vals['modified'] = $time_stamp;
		$result = $standard->save($vals, "update", $id);
	}else{
		$vals['created'] = $vals['modified'] = $time_stamp;
		$result = $standard->save($vals);
	}
	if (!$result) {
		flash();
	}
}
if (isset($_REQUEST['del']) && !empty($_REQUEST['id'])){
	$deleted = false;
	$deleted = $standard->del($_REQUEST['id']);
}
if (isset($_POST['do'])) {
	$do = trim($_POST['do']);
	if ($do == "save_standardtype") {
		$ins_arr = array();
		$tmp_arr = explode("\r\n", $_POST['data']['sort']);
		array_filter($tmp_arr);
		$i = 1;
		foreach ($tmp_arr as $key=>$val) {
			if(!empty($val))
			$ins_arr[$i] = "(".$i.",'".$val."')";
			$i++;
		}
		if (!empty($ins_arr)) {
			$pdb->Execute("TRUNCATE TABLE {$tb_prefix}standardtypes");
			$ins_str = "REPLACE INTO {$tb_prefix}standardtypes (id,name) VALUES ".implode(",", $ins_arr).";";
			$pdb->Execute($ins_str);
		}
		if($cache->updateTypes()){
			flash("success");
		}else{
			flash();
		}
	}
}
if (isset($_GET['do'])) {
	$do = trim($_GET['do']);
	if (isset($_GET['action'])) {
		$action = trim($_GET['action']);
	}
	if (!empty($_GET['id'])) {
		$id = intval($_GET['id']);
	}
	if ($do == "type") {
		$tpl_file = "standardtype";
		if (!empty($_PB_CACHE['standardtype'])) {
			setvar("sorts", implode("\r\n", $_PB_CACHE['standardtype']));
		}
		template($tpl_file);
		exit;
	}
	if ($do == "search") {
		if(!empty($_GET['q'])) {
			$search_title = $_GET['q'];
			$conditions = "title like '%".$search_title."%'";
		}
	}
	if ($do == "del" && !empty($id)){
		$deleted = false;
		$deleted = $standard->del($id);
	}
	if ($do == "edit") {
		if(!empty($id)){
			$item = $standard->read("*", $id);
			if(!empty($item['publish_time'])){
				$item['publish_date'] = df($item['publish_time']);
			}
			if(!empty($item['force_time'])){
				$item['force_date'] = df($item['force_time']);
			}
			if(!empty($item['attachment_id'])){
				$item['attach'] = $attachment_model->getAttachLink($item['attachment_id']);
			}
			setvar("item", $item);
		}
        setvar("StandardTypes",$_PB_CACHE['standardtype']);
		$tpl_file = "standard.edit";
		template($tpl_file, true);
	}	
}
$amount = $standard->findCount(null, $conditions);
$page->setPagenav($amount);
$result = $standard->findAll("Standard.*,t.name AS typename", array("LEFT JOIN {$tb_prefix}standardtypes t ON Standard.type_id=t.id"), $conditions, "Standard.id DESC", $page->firstcount, $page->displaypg);
setvar("Items", $result);
setvar("ByPages", $page->pagenav);
template($tpl_file);
?>