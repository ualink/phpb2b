<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
require("session_cp.inc.php");
uses("tradetype");
require(LIB_PATH. "cache.class.php");
require(LIB_PATH. "file.class.php");
$cache = new Caches();
$file = new Files();
$conditions = null;
$tradetype = new Tradetypes();
$tpl_file = "offertype";
if (isset($_POST['del']) && !empty($_POST['id'])) {
	$result = $tradetype->del($_POST['id']);
	if (!$result) {
		flash();
	}else{
		$cache->updateTypes();
	}
}
if (isset($_POST['update'])) {
	if (!empty($_POST['tid'])) {
		$type_count = count($_POST['tid']);
		$name_count = count($_POST['newname']);
		for($i=0; $i<$type_count; $i++){
			if (!empty($_POST['name'][$i])) {
				$pdb->Execute("UPDATE {$tb_prefix}tradetypes SET name='".$_POST['name'][$i]."',display_order='".$_POST['display_order'][$i]."',id=".$_POST['tid'][$i]." WHERE id=".$_POST['tid'][$i]);
			}
		}
		for($j=0; $j<$name_count; $j++){
			if (!empty($_POST['newname'][$j])) {
				$pdb->Execute("INSERT INTO {$tb_prefix}tradetypes (name,display_order,parent_id,level) values ('".$_POST['newname'][$j]."','".$_POST['newdisplayorder'][$j]."','".$_POST['newparentid'][$j]."',2)");
			}
		}
		$cache->updateTypes();
		flash("success");;
	}
}
if(isset($_POST['save'])){
	$id = $_POST['id'];
	$vals = $_POST['data']['tradetype'];
	if(!empty($id)){
		$result = $tradetype->save($vals, "update", $id);
	}else{
		$result = $tradetype->save($vals);
	}
	if(!$result){
		flash();
	}else{
		$cache->updateTypes();
	}
}
$fileststus = $file->safe_glob("../templates/default/offer.list*.html");
setvar("OfferListTemplates", $fileststus);
if (isset($_GET['do'])) {
	$do = trim($_GET['do']);
	if (!empty($_GET['id'])) {
		$id = intval($_GET['id']);
	}
	if ($do == "edit") {
		$tpl_file = "tradetype.edit";
		if (!empty($id)) {
			$result = $tradetype->read("*",$id);
			setvar("item",$result);
		}
	}
	if ($do == "del" && !empty($_GET['id'])){
		$result = $tradetype->del($_GET['id']);
		if (!$result) {
			flash();
		}else{
			$cache->updateTypes();
		}
	}
}
$sql = "SELECT * FROM {$tb_prefix}tradetypes";
$result = $pdb->GetArray($sql);
setvar("Items",$result);
template($tpl_file);
?>