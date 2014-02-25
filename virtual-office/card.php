<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2154 $
 */
require("../libraries/common.inc.php");
require("room.share.php");
uses("industry", "companyfield");
$industry = new Industries();
$companyfield = new Companyfields();
$tpl_file = "card";
if (empty($companyinfo['name'])) {
	flash("pls_complete_company_info", "company.php", 0);
}
if (isset($_POST['save'])) {
	pb_submit_check("company");
	$vals = array();
	$vals['link_man'] = $_POST['company']['link_man'];
	$vals['tel'] = $company->getPhone($_POST['data']['telcode'],$_POST['data']['telzone'],$_POST['data']['tel']);
	$vals['fax'] = $company->getPhone($_POST['data']['faxcode'],$_POST['data']['faxzone'],$_POST['data']['fax']);
	if(isset($_POST['company']['name'])) $vals['name'] = strip_tags($_POST['company']['name']);
	$vals['mobile'] = strip_tags($_POST['company']['mobile']);
	$vals['email'] = $_POST['company']['email'];
	$vals['address'] = $_POST['company']['address'];
	$company->primaryKey = "id";
	if (!empty($_POST['maplocation'])) {
		list($longi, $lati) = explode(",", $_POST['maplocation']);
		$pdb->Execute("REPLACE INTO {$tb_prefix}companyfields SET company_id=".$companyinfo['id'].",map_longitude='{$longi}',map_latitude='{$lati}'");
	}
	$vals = array_filter($vals);
	$result = $company->save($vals, "update", $companyinfo['id']);
	$pdb->Execute("DELETE FROM {$tb_prefix}spacecaches WHERE company_id='".$companyinfo['id']."'");
	if($result){
		$member->clearCache($the_memberid);
		$member->updateMemberCaches($the_memberid);
		flash("success");
	}else{
		flash("action_failed");
	}
}
if(!empty($companyinfo['name'])){
	list(,$companyinfo['telcode'], $companyinfo['telzone'], $companyinfo['tel']) = $company->splitPhone($companyinfo['tel']);
	list(,$companyinfo['faxcode'], $companyinfo['faxzone'], $companyinfo['fax']) = $company->splitPhone($companyinfo['fax']);
}
$companyfield->primaryKey = "company_id";
$companyfield_info = $companyfield->read("*", $companyinfo['id']);
$companyinfo = am($companyinfo, $companyfield_info);
setvar("item",$companyinfo);
vtemplate($tpl_file);
?>