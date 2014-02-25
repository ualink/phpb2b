<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
require("session_cp.inc.php");
uses("adzone");
require(PHPB2B_ROOT.'libraries/page.class.php');
$G['membergroup'] = cache_read("membergroup");
$tpl_file = "adzone";
$adzone = new Adzones();
$page = new Pages();
$conditions = null;
if (isset($_GET['do'])) {
	$do = trim($_GET['do']);
	if (!empty($_GET['id'])) {
		$id = intval($_GET['id']);
		setvar("id", $id);
	}
	if ($do=="del" && !empty($id)) {
		//check if have ad
		$all_ad = $pdb->GetOne("SELECT count(id) FROM {$tb_prefix}adses WHERE adzone_id=".$id);
		if ($all_ad>0) {
			flash("yet_some_ads");
		}else{
			$adzone->del($id);
		}
	}
	if($do == "makejs" && !empty($id)) {
		setvar("XMLDATA",'<{ads typeid='.$id.'}><a href="[link:url]">[field:src]</a><{/ads}>');
		template("adzone.makejs");
		exit;
	}
	if ($do == "edit") {
		$user_groups = array();
		foreach ($G['membergroup'] as $key=>$val) {
			$user_groups[$key] = $val['name'];
		}
		setvar("Membergroups", $user_groups);
		if (!empty($id)) {
			$result = $adzone->read("*", $id);
			if (!empty($result['membergroup_ids'])) {
				$tmp_arr = explode(",", $result['membergroup_ids']);
				$tmp_str = "['".implode("','", $tmp_arr)."']";
				$result['sel_membergroup_ids'] = $tmp_str;
			}
			setvar("item",$result);
		}
		$tpl_file = "adzone.edit";
		template($tpl_file);
		exit;
	}
}
if (isset($_POST['save'])) {
	$vals = $_POST['adzone'];
	$id = $_POST['id'];
	if (empty($vals['what'])) {
		$vals['what'] = 1;
	}
	if(!empty($_POST['membergroup_ids']) && !in_array(0, $_POST['membergroup_ids'])){
		$reses = implode(",", $_POST['membergroup_ids']);
		$vals['membergroup_ids'] = $reses;
	}elseif(!empty($_POST['membergroup_ids'])){
		$vals['membergroup_ids'] = 0;
	}	
	if (!empty($vals['additional_adwords'])) {
		$vals['additional_adwords'] = stripcslashes($vals['additional_adwords']);
	}
	if (!empty($id)) {
		$vals['modified'] = $time_stamp;
		$result = $adzone->save($vals, "update", $id);
		$adzone->updateBreathe($id);
	}else{
		$vals['created'] = $vals['modified'] = $time_stamp;
		$result = $adzone->save($vals);
	}
	if (!$result) {
		flash();
	}
}
if (isset($_POST['del']) && !empty($_POST['id'])) {
	$adzone->del($_POST['id']);
}
$amount = $adzone->findCount();
$page->setPagenav($amount);
$result = $adzone->findAll("*",null, $conditions, " id desc", $page->firstcount, $page->displaypg);
if (!empty($result)) {
	for($i=0; $i<count($result); $i++){
		$result[$i]['numbers'] = $pdb->GetOne("SELECT count(id) AS amount FROM {$tb_prefix}adses WHERE adzone_id=".$result[$i]['id']);
	}
	setvar("Items",$result);
	uaAssign(array("ByPages"=>$page->pagenav));
}
template($tpl_file);
?>