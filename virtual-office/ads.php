<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2115 $
 */
require("../libraries/common.inc.php");
require("room.share.php");
uses("adzone");
$tpl_file = "ads";
$adzone = new Adzones();
$payment = $pdb->GetArray("SELECT id,title FROM {$tb_prefix}payments WHERE available=1");
if (isset($_POST['do'])){
	pb_submit_check('do');
	uses("order");
	$order = new Orders();
	$result = $adzone->read("*", intval($_POST['id']));
	if (!empty($result)){
		$data['member_id'] = $the_memberid;
		$data['cache_username'] = $memberinfo['username'];
		$data['subject'] = $result['name'];
		$data['pay_id'] = $_POST['pay_id'];
		$data['pay_name'] = $_POST['pay_name'];
		$data['total_price'] = $result['price'];
		$new_trade_no = $order->Add($data);
		if (!empty($_POST['paynow'])){
			//header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
			pheader("Location:../purchase.php?do=pay&tradeno=".$new_trade_no);
			exit;
		}else{
			flash('success', 'order.php');
		}
	}
}
if (isset($_GET['do'])){
	$do = trim($_GET['do']);
	$id = intval($_GET['id']);
	if ($do=="buy" && !empty($id)){
		$result = $adzone->read("*", $id);
		if (!empty($result)){
			setvar("payments",$payment);
			setvar("item", $result);
			vtemplate("ads_edit");
			exit;
		}
	}
}
$result = $adzone->findAll("*",null, $conditions, " id desc");
setvar("datas",$result);
vtemplate($tpl_file);
?>