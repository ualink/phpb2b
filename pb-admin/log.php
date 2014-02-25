<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2197 $
 */
require("../libraries/common.inc.php");
uses("log");
require(PHPB2B_ROOT.'./libraries/page.class.php');
require("session_cp.inc.php");
$log = new Logs();
$page = new Pages();
$conditions = array();
if (isset($_POST['del']) && !empty($_POST['id'])) {
	$log->del($_POST['id']);
}
if (isset($_GET['do'])) {
	$do = trim($_GET['do']);
	if ($do == "clear") {
		$result = $pdb->Execute("truncate {$tb_prefix}logs");
	}
	if ($do == "del" && !empty($_GET['id'])) {
		$log->del($_GET['id']);
	}
	if($do == 'search'){
		if(!empty($_GET['q'])){
			$conditions[] = "description like '%".$_GET['q']."%'";
		}
	}
}
if (isset($_GET['q'])) {
	$conditions[] = "description like '%".$_GET['q']."%'";
}
$amount = $log->findCount(null, $conditions);
$page->setPagenav($amount);
$result = $log->findAll("id,handle_type,source_module,description,created,created AS pubdate", null, $conditions, "id DESC ",$page->firstcount,$page->displaypg);
if(!empty($result)){
	for($i=0; $i<count($result); $i++){
		$result[$i]['label'] = "../templates/admin/images/e_".$result[$i]['handle_type'].".gif";
		$result[$i]['pubdate'] = date("Y-m-d H:i:s", $result[$i]['created']);
	}
	setvar("Items", $result);
}
setvar("ByPages",$page->pagenav);
template("log");
?>