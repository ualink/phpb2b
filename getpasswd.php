<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2098 $
 */
define('CURSCRIPT', 'getpasswd');
require("libraries/common.inc.php");
require("share.inc.php");
require(LIB_PATH. "sendmail.inc.php");
uses("member");
$member = new Members();
if (isset($_POST['action'])) {
	pb_submit_check("data");
	$checked = true;
	$login_name = trim($_POST['data']['username']);
	$user_email = trim($_POST['data']['email']);
	if(!pb_check_email($user_email)){
		setvar("ERRORS", L("wrong_email_format"));
		$checked = false;
	}else{
		$member->setInfoByUserName($login_name);
		$member_info = $member->getInfo();
		if(!$member_info || empty($member_info)){
			setvar("ERRORS", L('member_not_exists'));
			setvar("postLoginName", $login_name);
			setvar("postUserEmail", $user_email);
			$checked = false;
		}elseif (!pb_strcomp($user_email, $member_info['email'])){
			setvar("ERRORS", L("please_input_email"));
			$checked = false;
		}
		if(!pb_check_email($member_info['email'])){
			$checked = false;
		}
		if ($checked) {
			$exp_time = $time_stamp + 86400;
			$hash = authcode(addslashes($member_info['username'])."\t".$exp_time,"ENCODE");
			setvar("hash", rawurlencode($hash));
			setvar("expire_date", date("Y-m-d H:i",strtotime("+1 day")));
			$sended = pb_sendmail(array($member_info['email'], $login_name), L("pls_reset_passwd"), "getpasswd");
			if(!$sended)
			{
				flash("email_send_false");
			}else{
				flash("getpasswd_email_sended");
			}
		}
	}
}
$viewhelper->setPosition(L("get_password", "tpl"));
formhash();
render("getpasswd");
?>