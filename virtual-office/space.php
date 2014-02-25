<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
require("room.share.php");
uses("templet");
check_permission("space");
$templet = new Templets();
if (!$company->Validate($companyinfo)) {
	flash("pls_complete_company_info", "company.php", 0);
}
if (isset($_POST['updateSpaceName']) && !empty($_POST['data']['space_name'])) {
	$space_name = trim($_POST['data']['space_name']);
	$i18n = new L10n();
	$space_name = $i18n->translateSpaceName($space_name);
	$result = $member->updateSpaceName(array("id"=>$the_memberid), $space_name);
	if ($result) {
		$member->clearCache($the_memberid);
		$member->updateMemberCaches($the_memberid);
		flash("success");
	}
}
if (isset($_POST['save']) && !empty($_POST['data']['member']['styleid'])) {
	$templet_id = intval($_POST['data']['member']['styleid']);
	$pdb->Execute("UPDATE {$tb_prefix}members SET templet_id=".$templet_id." WHERE id=".$the_memberid);
	$pdb->Execute("REPLACE INTO {$tb_prefix}spacecaches (cache_spacename,company_id,data1,data2) VALUE ('".$companyinfo['cache_spacename']."','".$companyinfo['id']."','".$templet_id."','".$_POST['data']['skin'][$templet_id]."')");
	$member->clearCache($the_memberid);
	$member->updateMemberCaches($the_memberid);
	flash("success");
}
setvar("templet_id", $memberinfo['templet_id']);
$cache_data = $pdb->GetRow("SELECT data2 AS style FROM {$tb_prefix}spacecaches WHERE company_id='".$companyinfo['id']."'");
/**
 * only for 4.2=>4.3 converts
 * 2012.9
 */
if(isset($cache_data['style'])) {
	setvar("style_id", $cache_data['style']);
}
$result = $templet->getInstalled($memberinfo['membergroup_id'], $memberinfo['membertype_id']);
foreach ($result as $key=>$val) {
	if (!is_dir(PHPB2B_ROOT."templates".DS.$val['directory'])) {
		unset($result[$key]);
	}elseif (!empty($result[$key]['description'])){
		$_styles = unserialize($result[$key]['description']);
		if (is_array($_styles)) {
			$result[$key]['styles'] = $_styles;
		}
	}
}
setvar("Items", $result);
vtemplate("space");
?>