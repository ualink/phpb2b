<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2258 $
 */
session_start();
define('CURSCRIPT', 'register');
require("libraries/common.inc.php");
require("share.inc.php");
require(LIB_PATH."sendmail.inc.php");
require(LIB_PATH.'passport.class.php');
//require(CACHE_LANG_PATH."lang_emails.php");
$G['membergroup'] = cache_read("membergroup");
$passport = new Passports();
uses("member","company","companyfield", "memberfield","membergroup");
$cfg['reg_time_seperate'] = 3*60;
$memberfield = new Memberfields();
$member = new Members();
$membergroup = new Membergroups();
$company = new Companies();
$companyfield = new Companyfields();
$check_invite_code = false;
$register_type = $G['setting']['register_type'];
$ip_reg_sep = $G['setting']['ip_reg_sep'];
$forbid_ip = $G['setting']['forbid_ip'];
$conditions = array();
capt_check("capt_register");
$tpl_file = "register";
$member_reg_auth = $G['setting']['new_userauth'];
if (isset($_GET['action'])) {
	$action = trim($_GET['action']);
	if ($action == "done") {
		$tpl_file = "register.done";
		$reg_tips = null;
		$reg_result = true;
		$is_company = false;
		if ($member_reg_auth) {
			switch ($member_reg_auth) {
				case 1:
					$reg_tips = L("pls_active_your_account", "msg", "index.php?do=member&action=reactive&em=".$_GET['em'])." [".date("Y-m-d H:i")."]";
					$reg_result = false;
					break;
				case 0:
					break;
				case 2:
					$reg_tips = L("pls_wait_for_check");
					$reg_result = false;
					break;
				default:
					$reg_tips = L("sth_wrong_occured");
					$reg_result = false;
					break;
			}
		}else{
			if (empty($pb_user)) {
				flash();
			}
			$member_info = $pdb->GetRow("SELECT membergroup_id,membertype_id FROM {$tb_prefix}members WHERE id='".$pb_user['pb_userid']."'");
			$gid = $member_info['membergroup_id'];
			$smarty->assign("groupname", $G['membergroup'][$gid]['name']);
			$smarty->assign("groupimg", STATICURL. "images/group/".$G['membergroup'][$gid]['avatar']);
			if ($member_info['membertype_id'] == 2) {
				$is_company = true;
			}
		}
		$smarty->assign("is_company", $is_company);
		$smarty->assign("result", $reg_result);
		$smarty->assign("RegTips", $reg_tips);
		render($tpl_file, true);
	}
}
if (!empty($ip_reg_sep)) {
	$cfg['reg_time_seperate'] = $ip_reg_sep*60*60;
}
if ($register_type=="close_register") {
	flash("register_closed", URL);
}elseif ($register_type=="open_invite_reg"){
	setvar("IfInviteCode", true);
	$check_invite_code = true;
}
if(isset($_POST['register'])){
	$is_company = false;
	$if_need_check = false;
	$register_type = trim($_POST['register']);
	$register_typename = trim($_POST['typename']);
	pb_submit_check('data');
	$default_membergroupid_res = $pdb->GetRow("SELECT * FROM {$tb_prefix}membertypes WHERE name='".$register_typename."'");
	$default_membergroupid = $default_membergroupid_res['default_membergroup_id'];
	if(empty($default_membergroupid)) $default_membergroupid = $membergroup->field("id","is_default=1");
	if ($default_membergroupid_res['id']>1) {
		$is_company = true;
	}
	$member->setParams();
	$memberfield->setParams();
	$member->params['data']['member']['membergroup_id'] = $default_membergroupid;
	$time_limits = $pdb->GetOne("SELECT default_live_time FROM {$tb_prefix}membergroups WHERE id={$default_membergroupid}");
	$member->params['data']['member']['service_start_date'] = $time_stamp;
	$member->params['data']['member']['service_end_date'] = $membergroup->getServiceEndtime($time_limits);
	$member->params['data']['member']['membertype_id'] = ($is_company)?2:1;
	if($member_reg_auth=="1" || $member_reg_auth!=0 || !empty($G['setting']['new_userauth'])){
		$member->params['data']['member']['status'] = 0;
		$if_need_check = true;
	}else{
		$member->params['data']['member']['status'] = 1;
	}
	$updated = false;
	$updated = $member->Add();
	if ($member_reg_auth == 1) {
		$if_need_check = true;
		$exp_time = $time_stamp+86400;
		$tmp_username = $member->params['data']['member']['username'];
		$hash = authcode("{$tmp_username}\t".$exp_time, "ENCODE");
//		$hash = str_replace(array("+", "|"), array("|", "_"), $hash);
		$hash = rawurlencode($hash);
		setvar("hash", $hash);
		setvar("expire_date", date("Y-m-d",strtotime("+1 day")));
		$sended = pb_sendmail(array($member->params['data']['member']['email'], $member->params['data']['member']['username']), $tmp_username.",".L("pls_active_your_account", "tpl"), "activite");
	}
	if (!empty($G['setting']['welcome_msg'])) {
		setvar("user_name", $member->params['data']['member']['username']);
		$sended = pb_sendmail(array($member->params['data']['member']['email'], $member->params['data']['member']['username']), L("thx_for_your_reg", "tpl", $G['setting']['site_name']), "welcome");
	}
	if($updated){
		$key = $member->table_name."_id";
		$last_member_id = $member->$key;
		if (empty($G['setting']['reg_filename'])) {
			$gopage = URL.'register.php?action=done&em='.urlencode($member->params['data']['member']['email']);
		}else{
			$gopage = URL.$G['setting']['reg_filename'].'?action=done&em='.urlencode($member->params['data']['member']['email']);
		}
		pheader("location:".$gopage);
	}else{
		setvar("member", $_POST['data']['member']);
		if(isset($_POST['data']['memberfield'])) setvar("memberfield", $_POST['data']['memberfield']);
	}
}
setvar("sid",md5(uniqid($time_stamp)));
setvar("agreement", $G['setting']['agreement']);
render($tpl_file);
?>