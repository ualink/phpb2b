<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
require("room.share.php");
require(PHPB2B_ROOT.'./libraries/page.class.php');
uses("spread");
$spread = new Spreads();
$page = new Pages();
$tpl_file = "spread";
$conditions = "member_id=".$the_memberid;
if (isset($_POST['save'])) {
	pb_submit_check('save');
	$record = array();
	$vals = $_POST['spread'];
	if (isset($_POST['id'])) {
		$id = intval($_POST['id']);
	}
	if (!empty($id)) {
		$updated = $spread->save($vals, 'update', $id, null, $conditions);
	}else{
		$vals['created'] = $time_stamp;
		//default one week
		$vals['expiration'] = 7*86400+$time_stamp;
		if (!empty($_POST['exp_date'])) {
			$vals['expiration'] = strtotime($_POST['exp_date']);
		}
		$vals['member_id'] = $the_memberid;
		$vals['status'] = 0;
		$updated = $spread->save($vals);
	}
	if (!$updated) {
		flash("action_failed");
	}else{
		flash("success", '', 0);
	}
}
if (isset($_GET['do'])){
	$do = trim($_GET['do']);
	if (isset($_GET['id'])) {
		$id = intval($_GET['id']);
	}
	if($do == "edit") {
		if (!empty($id)) {
			$linkinfo = $spread->read("*", $id, null, $conditions);
			setvar("item",$linkinfo);
		}
		$tpl_file = "spread_edit";
		vtemplate($tpl_file);
		exit;
	}
}
$amount = $spread->findCount(null, $conditions);
$page->setPagenav($amount);
$fields = "*";
$result = $spread->findAll($fields,null, $conditions,"id DESC",$page->firstcount,$page->displaypg);
for($i=0;$i<count($result);$i++){
	$result[$i]['exp_date'] = df($result[$i]['expiration']);
}
setvar("CheckStatus", cache_read('typeoption', 'check_status'));
setvar("Items", $result);
setvar("ByPages",$page->pagenav);
vtemplate($tpl_file);
?>