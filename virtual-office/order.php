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
uses("order");
$page = new Pages();
$page->displaypg = 25;
$options = cache_read("typeoption");
setvar("pay_status", $options['common_option']);
setvar("order_status", $options['common_status']);
$conditions[] = "member_id=".$the_memberid;
if (isset($_GET['action'])) {
	$action = trim($_GET['action']);
	if ($action=="cancel" && !empty($_GET['tradeno'])) {
		$pdb->Execute("DELETE FROM ".$tb_prefix."orders WHERE trade_no='".$_GET['tradeno']."' AND member_id='".$the_memberid."' AND pay_status='0'");
	}
}
$order = new Orders();
$amount = $order->findCount(null, $conditions);
$page->setPagenav($amount);
$result = $order->findAll("*",null, $conditions, " id desc", $page->firstcount,$page->displaypg);
setvar("ByPages",$page->pagenav);
setvar("datas",$result);
vtemplate("order");
?>