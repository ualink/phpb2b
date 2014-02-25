<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2258 $
 */
define('CURSCRIPT', 'pending');
require("libraries/common.inc.php");
require("share.inc.php");
uses("member");
$member = new Members();
$hash = null;
if(isset($_GET['hash'])) $hash = trim($_GET['hash']);
if (empty($hash)) {
	flash("invalid_request", null, 0);
}
$hash = rawurldecode($hash);
//$hash = str_replace(array("|", "_"), array("+", "|"), $hash);
$validate_str = authcode($hash, "DECODE");
if (empty($validate_str)) {
	flash("invalid_request", null, 0);
}
if (!empty($validate_str)) {
	list($tmp_username, $exp_time) = explode("\t", $validate_str);
    if ($exp_time<$time_stamp) {
    	flash("auth_expired", null, 0);
    }
    $user_exists = $member->checkUserExist($tmp_username, true);
    if ($user_exists && isset($_GET['action'])) {
    	switch ($_GET['action']) {
    		case "activate":
    			$result = $member->updateUserStatus($member->info['id']);
    			if ($result) {
    				flash("actived_and_login", "logging.php");
    			}
    			break;
    		case "getpasswd":
    			setvar("username", $member->info['username']);
    			$viewhelper->setPosition(L("reset_your_password", "tpl"));
    			render("getpasswd.pending");
    			break;
    		default:
    			break;
    	}
    }else{
        flash("member_not_exists", null, 0);
    }
}else{
	flash("invalid_request", null, 0);
}
?>