<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
require("session_cp.inc.php");
require(LIB_PATH. "cache.class.php");
require(LIB_PATH. "file.class.php");
$_PB_CACHE['userpage'] = cache_read("userpage");
uses("userpage");
$cache = new Caches();
$userpage = new Userpages();
$conditions = null;
$tpl_file = "userpage";
$file = new Files();
if (isset($_POST['del']) && is_array($_POST['id'])) {
	$deleted = $userpage->del($_POST['id']);
	if (!$deleted) {
		flash();
	}
	$cache->writeCache("userpage", "userpage");
}
if (isset($_POST['save'])) {
	$vals = array();
	$_POST['data']['userpage']['title'] = pb_lang_merge($_POST['data']['multi']);
	$_POST['data']['userpage']['content'] = pb_lang_merge($_POST['data']['multita']);
	$vals = $_POST['data']['userpage'];
	if(!empty($vals['title'])&&!empty($vals['name'])){
	if (!empty($_POST['id'])) {
		$vals['modified'] = $time_stamp;
		$result = $userpage->save($vals, "update", $_POST['id']);
	}else{
		$vals['created'] = $vals['modified'] = $time_stamp;
		$result = $userpage->save($vals);
	}
  }
	if (!$result) {
		flash();
	}
	$cache->writeCache("userpage", "userpage");
}
if (isset($_GET['do'])) {
	$do = trim($_GET['do']);
	if (!empty($_GET['id'])) {
		$id = intval($_GET['id']);
	}
	if ($do=="del" && !empty($id)) {
		$deleted = $userpage->del($id);
		$cache->writeCache("userpage", "userpage");
	}
	if ($do == "edit") {
		if(!empty($id)){
			$res= $userpage->read("*",$id);
			setvar("item",$res);
		}
		setvar("tplext", $smarty->tpl_ext);
		$tmp_pagetemplets = $file->getFiles(PHPB2B_ROOT."templates".DS.$theme_name);
		if (!empty($tmp_pagetemplets)) {
			$page_templets = "<optgroup label='".L("other_templet", "tpl")."'>";
			foreach ($tmp_pagetemplets as $p_val) {
				if (strstr($p_val['name'], "page.")) {
					$page_templets.= "<option value=".$p_val['name'].">".$p_val['name']."</option>";
				}
			}
			$page_templets.="</optgroup>";
			setvar("other_templets", $page_templets);
		}
		$tpl_file = "userpage.edit";
		template($tpl_file);
		exit;
	}
}
$result = $userpage->findAll("id,title,name,url,digest,display_order", null, $conditions, "display_order ASC,id ASC");
if (empty($result) && !empty($_PB_CACHE['userpage'])) {
	$result = $_PB_CACHE['userpage'];
	while (list($key, $val) = each($result)) {
		$tmp_arr[] = "('".$val['name']."','".$val['digest']."','".$val['title']."','".$val['url']."')";
	}
	$tmp_str = implode(",", $tmp_arr);
	$pdb->Execute("INSERT INTO ".$tb_prefix."userpages (name,digest,title,url) VALUES ".$tmp_str);
}
setvar("Items", $result);
template($tpl_file);
?>