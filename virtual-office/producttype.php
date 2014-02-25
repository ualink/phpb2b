<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
require("room.share.php");
uses("product","producttype");
$tpl_file = "producttype";
$product = new Products();
$producttype = new Producttypes();
$conditions[] = "member_id = ".$the_memberid;
if (!$company->Validate($companyinfo)) {
	flash("pls_complete_company_info", "company.php", 0);
}
if (isset($_GET['do'])){
	$do = trim($_GET['do']);
	if (isset($_GET['id'])) {
		$id = intval($_GET['id']);
	}
	if($do=="del" && !empty($id)) {
		$product_amount = $pdb->GetOne("SELECT count(id) FROM {$tb_prefix}products WHERE type_id={$id} AND member_id={$the_memberid}");
		if ($product_amount>0) {
			flash('pls_del_first');
		}else{
			$result = $producttype->del($id, $conditions);
		}
	}
	if ($do=="edit") {
		$company->newCheckStatus($companyinfo['status']);		
		if (!empty($id)) {
			$res = $producttype->read('id,name', $id, null, $conditions);
			setvar("item",$res);
		}
		$tpl_file = "producttype_edit";
		vtemplate($tpl_file);
		exit;
	}
}
if (isset($_POST['save']) && !empty($_POST['data']['name'])) {
	pb_submit_check('data');
	$record = array();
	if (isset($_POST['id'])) {
		$id = intval($_POST['id']);
	}
	$record['name'] = $_POST['data']['name'];
	if(!empty($id)){
		$record['modified'] = $time_stamp;
		$result = $producttype->save($record, "update", $id, null, $conditions);
	}else{
		$orignal_count = $producttype->findCount(null, $conditions);
		if ($g['max_producttype'] && $orignal_count>=$g['max_producttype']) {
			flash("post_max");
		}
		$record['member_id'] = $the_memberid;
		$record['created'] = $record['modified'] = $time_stamp;
		$record['company_id'] = $company_id;
		$result = $producttype->save($record);
	}
	if(!$result){
		flash();
	}
}

$result = $producttype->findAll('id,name',null,$conditions," id DESC");
setvar("Items",$result);
vtemplate($tpl_file);
?>