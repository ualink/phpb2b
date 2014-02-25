<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
require("room.share.php");
uses("trade","product");
check_permission("offer");
$product = new Products();
$trade = new Trades();
$trade_controller = new Trade();
$trade_type_names = $trade_controller->getTradeTypes();
$conditions = "member_id = ".$the_memberid;
$amount = $pdb->GetArray("select Trade.type_id as TradeTypeId,count(Trade.id) as CountTrade from ".$trade->getTable(true)." where ". $conditions. " group by Trade.type_id");
if(is_array($amount))
{
	$stats = array();
	foreach ($amount as $val) {
		$stats[$val['TradeTypeId']] = array("Amount"=>$val['CountTrade'], "name"=>$trade_type_names[$val['TradeTypeId']]);
	}
}
setvar("UserTradeStat",$stats);
setvar("ProductAmount",$product->findCount(null, $conditions,"Product.id"));
vtemplate("stat");
?>