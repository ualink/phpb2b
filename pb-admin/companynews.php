<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2163 $
 */
require("../libraries/common.inc.php");
uses("companynews","company","member");
require(PHPB2B_ROOT.'libraries/page.class.php');
require("session_cp.inc.php");
$member = new Members();
$page = new Pages();
$company = new Companies();
$companynews = new Companynewses();
$conditions = $joins = array();
$tpl_file = "companynews";
$common_status = cache_read("typeoption", "common_status");
if (isset($_POST['del']) && is_array($_POST['id'])) {
	if (!$companynews->del($_POST['id'])) {
		flash();
	}
}
if (isset($_POST['check']) && (!empty($_POST['id'])) && is_array($_POST['id'])) {
	$strCompanyNewsId = implode(",", $_POST['id']);
	$strCompanyNewsId = "(".$strCompanyNewsId.")";
	$arrResult = $pdb->GetArray("select id,status from ".$companynews->getTable()." where id in ".$strCompanyNewsId);
	if (!empty($arrResult)){
	    foreach ($arrResult as $key=>$val){
	        if (1 == $val['status']) {
	        	$result = $pdb->Execute("update ".$companynews->getTable()." set status='0' where id=".$val['id']);
	        }else{
	            $result = $pdb->Execute("update ".$companynews->getTable()." set status='1' where id=".$val['id']);
	        }
	    }
	    if(!$result){
	    	flash();
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
	if ($do == "del" && $id) {
		if (!$companynews->del($id)) {
			flash();
		}
	}
	if ($do == 'search') {
		if (!empty($_GET['topic'])) $conditions[]= "Companynews.title like '%".trim($_GET['topic'])."%'";
		if (!empty($_GET['membername'])) $conditions[]= "Member.name='".$_GET['membername']."'";
		if (!empty($_GET['companyname'])) $conditions[]= "Company.company_name like '%".$_GET['companyname']."%'";
	}
	if ($do == "view") {
		if($id){
			$news_info = $pdb->GetRow("SELECT cn.*,c.name companyname FROM {$tb_prefix}companynewses cn LEFT JOIN {$tb_prefix}companies c ON c.id=cn.company_id WHERE cn.id=".$id);
			setvar("item",$news_info);
		}
		$tpl_file = "companynews.view";
		template($tpl_file);
		exit;
	}
}
if (isset($_GET['q'])) {
	$conditions[] = "title like '%".$_GET['q']."%'";
}
$fields = "company_id,Companynews.id,Companynews.title,Companynews.status,Companynews.status as CompanynewsStatus,Companynews.created,Companynews.clicked,c.name AS companyname";
$amount = $companynews->findCount(null, $conditions,"Companynews.id");
$page->setPagenav($amount);
$joins[] = "LEFT JOIN {$tb_prefix}companies c ON c.id=Companynews.company_id";
$result = $companynews->findAll($fields, $joins, $conditions, "Companynews.id DESC ",$page->firstcount,$page->displaypg);
if (!empty($result)) {
	for($i=0; $i<count($result); $i++){
		$result[$i]['pubdate'] = df($result[$i]['created']);
		$result[$i]['common_status'] = $common_status[$result[$i]['status']];
	}
	setvar("Items", $result);
}
uaAssign(array("ByPages"=>$page->pagenav, "TotalAmount"=>$amount));
template($tpl_file);
?>