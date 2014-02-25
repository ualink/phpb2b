<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
require("room.share.php");
uses("trade");
$trade = new Trade();
$trade_model = new Trades();
if (isset($_POST['del'])) {
	pb_submit_check('id');
	$ids = implode(",", $_POST['id']);
	$ids = "(".$ids.")";
	$sql = "DELETE FROM {$tb_prefix}favorites WHERE id IN ".$ids." AND member_id=".$the_memberid;
	$res = $pdb->Execute($sql);
	if (!$res) {
		flash("action_failed");
	}
}
if(isset($_POST['do']) && isset($_POST['id'])){
	//check limit
	$type_id = 1;
	$f_limit = $pdb->GetOne($sql = "SELECT count(id) FROM {$tb_prefix}favorites WHERE type_id='".$type_id."' AND member_id=".$the_memberid);
	if ($trade_model->checkExist($_POST['id'])) {
		if ($g['max_favorite']==0 or $g['max_favorite']>$f_limit) {
			$sql = "INSERT INTO {$tb_prefix}favorites (target_id,member_id,type_id,created,modified) VALUE (".$_POST['id'].",".$the_memberid.",".$type_id.",".$time_stamp.",".$time_stamp.")";
			$result = $pdb->Execute($sql);
		}else{
			flash("post_max");
		}
	}else{
		flash("data_not_exists");
	}
	if($result){
		echo "<script language='javascript'>window.close();</script>";
		exit;
	}else {
		flash("been_favorited", '', 0);
	}
}
$tpl_file = "favor";
$sql = "select f.id,t.id as offerid,t.title,t.type_id,f.created from {$tb_prefix}trades as t,{$tb_prefix}favorites as f where f.member_id=".$pb_userinfo['pb_userid']." and f.target_id=t.id";
$result = $pdb->GetArray($sql);
if (!empty($result)) {
	for($i=0; $i<count($result); $i++){
		$result[$i]['pubdate'] = df($result[$i]['created']);
	}
	setvar("Items", $result);
}
setvar("TradeTypes", $trade->getTradeTypes());
vtemplate($tpl_file);
?>