<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2186 $
 */
define('CURSCRIPT', 'upgrade');
require("libraries/common.inc.php");
require("share.inc.php");
session_start();
uses("member","order","good","payment");
$member = new Members();
$goods = new Goods();
$order = new Orders();
$payment_controller = new Payment();
$adzones = $pdb->GetArray("SELECT id,name,price FROM {$tb_prefix}adzones");
$payment = $pdb->GetArray("SELECT id,title,description FROM {$tb_prefix}payments WHERE available=1");
if (!empty($pb_userinfo['pb_userid'])) {
	$member_info = $pdb->GetRow("SELECT m.*,mf.tel,mf.first_name,mf.last_name FROM {$tb_prefix}members m LEFT JOIN {$tb_prefix}memberfields mf ON m.id=mf.member_id WHERE m.id=".$pb_userinfo['pb_userid']);
	setvar("MemberInfo", $member_info);
}else{
	flash("please_login_first", URL."logging.php");
}
/* get payment code, local extra param sended to remote server */
$pay_code = '';
$pay_code = !empty($_REQUEST['code']) ? trim($_REQUEST['code']) : '';

if (!empty($pay_code))
{
	$payment_controller->setPay($pay_code);
	$payer = $payment_controller->getPay();
	if (is_object($payer)) {
		$pay_result = $payer->respond();
		if ($pay_result){
			setvar("pay_result", 1);
			setvar("pay_msg", L("pay_success"));
		}else{
			setvar("pay_result", 0);
			setvar("pay_msg", L("pay_error"));
		}
		setvar("item", $_GET);
		$smarty->display("payments/".$pay_code."/done".$smarty->tpl_ext);
		exit;
	}else{
		flash();
	}
}

if(isset($_GET['do'])){
    $do = trim($_GET['do']);
    $pay_method = null;
    if (empty($_GET['pay_method'])) {
    	$pay_method = "alipay";
    }else{
    	$pay_method = trim($_GET['pay_method']);
    }
    if($do == "pay" || $do=="charge"){
    	//by the trade_no,get order info and payment info.
    	if (!empty($_GET['tradeno'])) {
    		$order_result = $pdb->GetRow("SELECT * FROM ".$tb_prefix."orders WHERE trade_no='".$_GET['tradeno']."'");
    		if (!empty($order_result)) {
    			$payment_info = $pdb->GetRow("SELECT * FROM ".$tb_prefix."payments WHERE id=".$order_result['pay_id']);
    			//check pay_method
    			if (empty($payment_info)) {
    				flash(L("data_not_exists"), "index.php");
    			}
    			$pay_method = $payment_info['name'];
    			setvar("item", $order_result);
    		}
    	}
    	$payment_info = $pdb->GetRow("SELECT * FROM ".$tb_prefix."payments where name='".$pay_method."'");
    	setvar("payment_info", $payment_info);
    	$smarty->display($tpl_dir. "/payments/".$pay_method."/index".$smarty->tpl_ext);
    	exit;
	}
}
if (isset($_POST['do'])) {
	$do = trim($_POST['do']);
	pb_submit_check('do');
	$order_pay_code = trim($_REQUEST['pay_method']);
	if (empty($order_pay_code)) {
		$order_pay_code = "alipay";
	}
	switch ($do) {
		case "paynow":
    		$payment_controller->setPay($order_pay_code);
    		$payer = $payment_controller->getPay();
    		if (!empty($_POST['tradeno'])) {
    			$order_result = $pdb->GetRow("SELECT * FROM ".$tb_prefix."orders WHERE trade_no='".$_POST['tradeno']."' AND status=0 AND pay_status=0");
    			if (!empty($order_result)){
    				$pdb->Execute("UPDATE ".$tb_prefix."orders set modified=".$time_stamp." WHERE trade_no='".$_POST['tradeno']."' AND status=0 AND pay_status=0");
    				$order_result['content'] = htmlspecialchars($_POST['body']);
    			}else{
    				//error
    				flash('trade_no_error');
    			}
    		}else{
    			//If not exists, create a new order.
    			$order_result['member_id'] = $member_info['id'];
    			$order_result['cache_username'] = $member_info['username'];
    			$order_result['content'] = htmlspecialchars($_POST['body']);
    			$order_result['total_price'] = $_POST['money'];
    			$new_trade_no = $order->Add($order_result);
    			$order_result['trade_no'] = $new_trade_no;
    		}
    		$tmp = $pdb->GetRow("SELECT * FROM ".$tb_prefix."payments WHERE name='".$order_pay_code."' AND available=1");
    		if (!empty($tmp['config'])) {
    			$payment_config = unserialize($tmp['config']);
    		}
    		$payer->redirect($order_result, $payment_config);
			break;
		case "buynow":
			//If not exists, create a new order.
			$data['member_id'] = $member_info['id'];
			$data['cache_username'] = $member_info['username'];
			$data['content'] = htmlspecialchars($_POST['content']);
			//get price by good_id
			$info = $goods->read("*", $_POST['good_id']);
			if (!empty($_POST['payment_id'])) {
				$data['pay_id'] = intval($_POST['payment_id']);
			}
			if (!empty($info)){
				$data['total_price'] = $info['price'];
				$data['subject'] = $info['name'];
				$new_trade_no = $order->Add($data);
			}
			//get payment information
    		$tmp = $pdb->GetRow("SELECT * FROM ".$tb_prefix."payments WHERE id='".$_POST['payment_id']."' AND available=1");
			setvar("OnlineSupport", $tmp['if_online_support']);
			setvar("tradeno", $new_trade_no);
			render("member.pay", 1);
			break;
		default:
			break;
	}
}
$viewhelper->setPosition(L("select_buy_service", "tpl"));
setvar("payments",$payment);
render("member.purchase");
?>