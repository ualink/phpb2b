<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2133 $
 */
session_start();
define('IN_OFFCE', TRUE);
$office_theme_name = "";
$G['membergroup'] = cache_read("membergroup");
$G['type'] = cache_read("type");
$G['typeoption'] = cache_read("typeoption");
uses("member", "memberfield", "company");
$member = new Members();
$memberfield = new Memberfields();
$company = new Companies();
$company_controller = new Company();
$smarty->setTemplateDir(PHPB2B_ROOT. "templates/office2012/");
$sections = array('office','message');
$smarty->configLoad('default.conf', $sections);
setvar("office_theme_path", "../templates/office2012/");
$smarty->setCompileDir($smarty->getCompileDir().$viewhelper->office_dir.DS);
$check_invite_code = false;
$pdb->setFetchMode(ADODB_FETCH_ASSOC);
$ADODB_CACHE_DIR = DATA_PATH.'dbcache';
if (isset($G['setting']['register_type'])) {
	$register_type = $G['setting']['register_type'];
	if ($register_type=="open_invite_reg"){
	    setvar("IfInviteCode", true);
	}
}
if (empty($_SESSION['MemberID']) || empty($_SESSION['MemberName'])) {
	uclearcookies();
	if (isset($_POST['is_ajax']) && $_POST['is_ajax']) {
		die(strip_tags(L("please_login_first")));
	}
	pheader("location:".URL."logging.php?forward=".urlencode(pb_get_host().$php_self));
}
$the_memberid = intval($_SESSION['MemberID']);
$the_membername = $_SESSION['MemberName'];
//if caches
$cache_data = array();
$pdb->Execute("DELETE FROM {$tb_prefix}membercaches WHERE expiration<".$time_stamp);
$result = $pdb->GetRow("SELECT data1 AS info FROM `{$tb_prefix}membercaches` WHERE member_id='".$the_memberid."'");
if (empty($result)) {
	$cache_data = $member->updateMemberCaches($the_memberid);
}else{
	$cache_data = @unserialize($result['info']);
}
$memberinfo = $cache_data['member'];
$companyinfo = $cache_data['company'];
$company_id = $companyinfo['id'];
setvar("COMPANYINFO", $companyinfo);
$g = $G['membergroup'][$memberinfo['membergroup_id']];
if (!empty($g['auth_level'])) {
	$auth = sprintf("%05b", $g['auth_level']);
	$menu['basic'] = $auth[0];
	$menu['offer'] = $auth[1];
	$menu['product'] = $auth[2];
	$menu['company'] = $auth[3];
	$menu['pms'] = $auth[4];
	setvar("menu", $menu);
}
function check_permission($perm)
{
	global $g, $smarty;
	$allow = ($perm=="space")? "allow_space" : $perm."_allow";
	if (!$g[$allow]) {
		$message = L("have_no_perm", "msg", L($allow, "tpl"));
		$smarty->assign('action_img', "failed.png");
		$smarty->assign('url', 'javascript:;');
		$smarty->assign('message', $message);
		$smarty->assign('title', $message);
		$smarty->assign('page_title', strip_tags($message));
		vtemplate($smarty->flash_layout);
		exit();
	}
}

//for office
function vtemplate($filename = null, $exit = false)
{
	global $smarty;
	$return = false;
	$return = $smarty->display("extends:layout".$smarty->tpl_ext."|".$filename.$smarty->tpl_ext);
	if ($exit) {
		exit;
	}
	return $return;
}
$new_pm = $cache_data['message']['new_pm'];
setvar("newpm", (empty($new_pm) || !$new_pm)? false : $new_pm);
$user_name = (!empty($memberinfo['last_name']))?$memberinfo['first_name'].$memberinfo['last_name']:$_SESSION['MemberName'];
setvar("UserName", $user_name);
$memberinfo['start_date'] = df($memberinfo['service_start_date']);
if($memberinfo['service_end_date'])
$memberinfo['end_date'] = df($memberinfo['service_end_date']);
else
$memberinfo['end_date'] = L("permanent", "tpl");
$memberinfo['gender_name'] = $G['typeoption']['calls'][$memberinfo['gender']];
$memberinfo['avatar'] = (!empty($memberinfo['photo']))?pb_get_attachmenturl($memberinfo['photo'], "../", "small"):(($memberinfo['gender']==2)?"../static/images/avatar/female.png":"../static/images/avatar/male.png");
setvar("MemberInfo", $memberinfo);
$today_start = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
?>