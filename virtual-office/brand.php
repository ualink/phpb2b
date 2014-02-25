<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
require("room.share.php");
require(LIB_PATH. 'page.class.php');
require(LIB_PATH. 'cache.class.php');
uses("brand","attachment","brandtype");
require(LIB_PATH .'time.class.php');
require(CACHE_COMMON_PATH."cache_type.php");
$cache = new Caches();
$attachment = new Attachment('pic');
$brandtypes = new Brandtypes();
$brand = new Brands();
$page = new Pages();
$tpl_file = "brand";
if (!$company->Validate($companyinfo)) {
	flash("pls_complete_company_info", "company.php", 0);
}
if (isset($_POST['save']) && !empty($company_id)) {
	$company->newCheckStatus($companyinfo['status']);
	if(!empty($_POST['data']['brand'])){
		$vals = $_POST['data']['brand'];
		if(isset($_POST['id'])){
			$id = intval($_POST['id']);
		}
		$attachment->rename_file = "brand-".($brand->getMaxId()+1);
		if(!empty($id)){
			$attachment->insert_new = false;
			$attachment->rename_file = "brand-".$id;
		}
		if (!empty($_FILES['pic']['name'])) {
			$attachment->upload_process();
			$vals['picture'] = $attachment->file_full_url;
		}
		if (!empty($vals['description'])) {
			$vals['description'] = stripcslashes($vals['description']);
		}
		$vals['letter'] = L10n::getinitial($vals['name']);
		$vals['member_id'] = $the_memberid;
		$vals['company_id'] = $company_id;
		if (!empty($id)) {
			$vals['modified'] = $time_stamp;
			$res = $brand->save($vals, "update", $id, null, $conditions);
		}else{
			$vals['created'] = $vals['modified'] = $time_stamp;
			$res = $brand->save($vals);
		}
		$cache->updateIndexCache();
		if(!$res) {
			flash("action_failed");
		}else{
			flash("success");
		}
	}
}
if (isset($_GET['do'])) {
	$do = trim($_GET['do']);
	if (!empty($_GET['id'])) {
		$id = intval($_GET['id']);
	}
	if($do=="del" && !empty($id)) {
		$result = $brand->del(intval($id), "member_id=".$the_memberid);
	}
	if ($do=="edit") {
		if (!empty($id)) {
			$brand_info = $pdb->GetRow("SELECT * FROM {$tb_prefix}brands WHERE member_id=".$the_memberid." AND id={$id}");
			if (!empty($brand_info['picture'])) {
				$brand_info['image'] = pb_get_attachmenturl($brand_info['picture'], "../");
			}
			setvar("item", $brand_info);
		}
		setvar("BrandtypeOptions", $brandtypes->getTypeOptions());
		$tpl_file = "brand_edit";
		vtemplate($tpl_file);
		exit;
	}
}
$conditions[] = "member_id=".$the_memberid;
$amount = $brand->findCount('', $conditions);
$page->setPagenav($amount);
$result = $brand->findAll("id,name,description,picture", '', $conditions, "id DESC", $page->firstcount, $page->displaypg);
if (!empty($result)) {
	for($i=0; $i<count($result); $i++){
		$result[$i]['image'] = pb_get_attachmenturl($result[$i]['picture'], '../');
	}
	setvar("Items", $result);
	setvar("ByPages", $page->pagenav);
}
vtemplate($tpl_file);