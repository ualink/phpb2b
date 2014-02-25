<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2133 $
 */
require("../libraries/common.inc.php");
require("room.share.php");
$spacelink = new Spaces();
$tpl_file = "link";
if (!$company->Validate($companyinfo)) {
	flash("pls_complete_company_info", "company.php", 0);
}
if (isset($_POST['delete'])) {
	$deleted = false;
	if (is_array($_POST['id'])) {
		$ids = "(".implode(",", $_POST['id']).")";
		$deleted = $pdb->Execute("DELETE FROM {$tb_prefix}spacelinks WHERE member_id={$the_memberid} AND id IN $ids");
		if($deleted){
			flash("success");
		}else{
			flash();
		}
	}else{
		flash("no_data_deleted");
	}
}
if (isset($_POST['save'])) {
	$record = array();
	$vals = $_POST['spacelink'];
	if (isset($_POST['id'])) {
		$id = intval($_POST['id']);
	}
	if (!empty($id)) {
		$updated = $spacelink->add($vals, "update", $id, "{$tb_prefix}spacelinks", "member_id=".$the_memberid." AND company_id=".$companyinfo['id']);
	}else{
		if ($pdb->GetOne("SELECT id FROM {$tb_prefix}spacelinks WHERE member_id=".$the_memberid." AND url='".$vals['url']."'")) {
			if (isset($_POST['is_ajax'])) {
				die(L("companyname_has_exists", 'msg', L('company_name')));
			};
		}else{
			$vals['created'] = $time_stamp;
			$vals['company_id'] = $companyinfo['id'];
			$vals['member_id'] = $the_memberid;
			if (strstr($vals['url'], 'http://') || strstr($vals['url'], 'www')) {
				$vals['is_outlink'] = 1;
			}
			$updated = $spacelink->save($vals, '', '', "{$tb_prefix}spacelinks");
		}
	}
	if (isset($_POST['is_ajax'])) {
		die(L("success"));
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
			$linkinfo = $spacelink->read("*", $id);
			setvar("item",$linkinfo);
		}
		$tpl_file = "link_edit";
		vtemplate($tpl_file);
		exit;
	}
}
$result = $spacelink->getSpaceLinks($the_memberid, $companyinfo['id']);
if (!empty($result)) {
	setvar("Items", $result);
}
vtemplate($tpl_file);
?>