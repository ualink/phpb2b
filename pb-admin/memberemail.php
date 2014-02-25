<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
uses("member", "membergroup");
require("session_cp.inc.php");
require(CACHE_COMMON_PATH."cache_type.php");
$G['membergroup'] = cache_read("membergroup");
require(LIB_PATH. "sendmail.inc.php");
$member = new Members();
$membergroup = new Membergroup();
$conditions = array();
$tpl_file = "member.email";
setvar("Membertypes", $_PB_CACHE['membertype']);
setvar("MembergroupOptions", $membergroup->getUsergroups());
setvar("Membergroups", $G['membergroup']);
if (isset($_POST['send'])) {
	$vals = $_POST['data'];
	if ($vals['membertype_id']) {
		$conditions[] = "membertype_id=".$vals['membertype_id'];
	}
	if ($vals['membergroup_id']) {
		$conditions[] = "membergroup_id=".$vals['membergroup_id'];
	}
	$limit = null;
	if ($vals['all']) {
		;	
	}else{
		if ($vals['day']>0) {
			$day_timestamp = $vals['day']*86400;
			$day_timestamp = $time_stamp-$day_timestamp;
			$conditions[] = "last_login<".$day_timestamp;
		}
		if ($vals['id']['from'] && $vals['id']['to']) {
			$conditions[] = "id BETWEEN ".$vals['id']['from']." AND ".$vals['id']['to'];
		}else{
			$limit = 100;
		}
	}
	$pdb->setFetchMode(ADODB_FETCH_ASSOC);
	$email_tos = $member->findAll("username,email", null, $conditions, null, $limit);
	$result = pb_sendmail($email_tos, $vals['subject'], null, $vals['content']);
	if($result){
		flash("email_sended_success");
	}else{
		flash("email_sended_false");
	}
}
template($tpl_file);
?>