<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
require(LIB_PATH .'page.class.php');
require("session_cp.inc.php");
uses("order", "typeoption");
$order = new Orders();
$typeoption = new Typeoption();
$tpl_file = "order";
$page = new Pages();
setvar("Status", $typeoption->get_cache_type("common_status"));
if (isset($_POST['status'])) {
	$id = $_POST['id'];
	$tmp_to = intval($_POST['status']);
	if (!empty($id)) {
		$result = $order->checkOrders($id, $tmp_to);
	}
	if (!$result) {
		flash();
	}
}
if (isset($_POST['del']) && !empty($_POST['id'])) {
	$result = $order->del($_POST['id']);
}
if (isset($_GET['do'])){
	$do = trim($_GET['do']);
	if (!empty($_GET['id'])) {
		$id = intval($_GET['id']);
	}
	if($do=="del" && !empty($id)) {
		$result = $order->del($_GET['id'])	;
	}
	if ($do == "edit") {
		if (!empty($id)) {
			$result = $order->read("*", $id);
			setvar("item",$result);
		}
		$tpl_file = "order.edit";
		template($tpl_file);
		exit;
	}
	if ($do == "view") {
		if (!empty($id)) {
			$order_content = $pdb->GetOne("SELECT content FROM {$tb_prefix}orders WHERE id={$id}");
			$sql = "SELECT g.name,og.amount,g.price,og.order_id,og.goods_id,o.total_price,o.content FROM {$tb_prefix}ordergoods og LEFT JOIN {$tb_prefix}goods g ON g.id=og.goods_id LEFT JOIN {$tb_prefix}orders o ON o.id=og.order_id WHERE og.order_id=".$id;
			if (!empty($order_content)) {
				$contents = explode("|", $order_content);
				$product_id = $contents[0];
				if (!empty($product_id)) {
					if ($product_id == 3) {
						$sql = "SELECT g.name,og.amount,g.price,og.order_id,og.goods_id FROM {$tb_prefix}ordergoods og LEFT JOIN {$tb_prefix}adzones g ON g.id=og.goods_id WHERE og.order_id=".$id;
					}
				}
			}
			$tpl_file = "order.goods";
			$result = $pdb->GetArray($sql);
			if (!empty($result)) {
				$total_price = 0;
				for ($i=0; $i<count($result); $i++){
					$tmp_str = explode("|", $result[$i]['content']);
					if($result[$i]['goods_id']=="2" && strpos($result[$i]['content'],"|")===false){
						$total_price = $result[$i]['total_price'];
						$result[$i]['price'] = $result[$i]['total_price'];
						$result[$i]['charge'] = 1;
					}else{
						$total_price += $result[$i]['price']*$result[$i]['amount'];
					}
				}
				setvar("Items",$result);
				setvar("TotalPrice", $total_price);
			}
			template($tpl_file);
			exit;
		}
	}
}
$joins[] = "LEFT JOIN {$tb_prefix}members m ON m.id=Orders.member_id LEFT JOIN {$tb_prefix}memberfields mf ON mf.member_id=Orders.member_id";
$amount = $order->findCount($joins, null, "Orders.id");
$page->setPagenav($amount);
$result = $order->findAll("Orders.*,m.username,mf.first_name,mf.last_name as true_name", $joins, $conditions, " Orders.id DESC", $page->firstcount, $page->displaypg);
setvar("Items",$result);
setvar("ByPages",$page->pagenav);
template($tpl_file);
?>