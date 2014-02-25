<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2154 $
 */
require("../libraries/common.inc.php");
require("room.share.php");
require(CACHE_COMMON_PATH. "cache_typeoption.php");
uses("membergroup", "trade");
$membergroup = new Membergroups();
$trade = new Trade();
setvar("MenuHide", "display:none;");
if (!empty($memberinfo)) {
	$service_info = false;
	$membergroup_id = $memberinfo['membergroup_id'];
	if (empty($memberinfo['service_end_date']) or empty($memberinfo['service_start_date'])) {
		$service_info = false;
	}else{
		$total_days = ($memberinfo['service_end_date'] - $memberinfo['service_start_date']);
		if($total_days<=0) {
			$total_days=1;
			$service_interation = 1;
		}else{
			$service_interation = intval((($time_stamp - $memberinfo['service_start_date'])/$total_days)*100);
		}
		setvar("service_days", $service_interation>100?100:$service_interation);
		$service_info = true;
	}
	if(isset($service_interation))
	if ($service_interation>=100) {
		$group_info = $pdb->GetRow("SELECT default_live_time,after_live_time FROM {$tb_prefix}membergroups WHERE id=".$membergroup_id);
		$membergroup_id = $group_info['after_live_time'];
		$time_add = $membergroup->getServiceEndtime($group_info['default_live_time']);
		$pdb->Execute("UPDATE {$tb_prefix}members SET membergroup_id='".$group_info['after_live_time']."' WHERE id=".$the_memberid);
	}
	uaAssign(array(
		"LastLogin"=>date("Y-m-d H:i",$memberinfo['last_login']))
	);
	$offer_count = $pdb->GetArray("SELECT count(id) AS amount,type_id AS typeid FROM {$tb_prefix}trades WHERE member_id=".$the_memberid." GROUP BY type_id");
	$offer_stat = array();
	$types = $trade->getTradeTypes();
	if (!empty($offer_count)) {
		foreach ($offer_count as $offer_key=>$offer_val) {
			$offer_stat[$types[$offer_val['typeid']]] = $offer_val['amount'];
		}
		setvar("items_offer", $offer_stat);
	}
	$pm_count = $pdb->GetArray("SELECT count(id) AS amount,type AS typename FROM {$tb_prefix}messages WHERE to_member_id=".$the_memberid." GROUP BY type");
	if (!empty($pm_count)) {
		$pm_result = array();
		foreach ($pm_count as $pm_val) {
			$pm_result[$pm_val['typename']] = intval($pm_val['amount']);
		}
		setvar("pm", $pm_result);
	}
	setvar("ServiceInfo", $service_info);
	$group['name'] = $g['name'];
	$group['image'] = $g['avatar'];
	setvar("group", $group);
	vtemplate("index");
}else{
	flash('invalid_user');
}
?>