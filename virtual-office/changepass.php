<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
require("room.share.php");
require(LIB_PATH.'passport.class.php');
$passport = new Passports();
if (isset($_POST['do']) || isset($_POST['action'])) {
	$do = trim($_POST['do']);
	$action = trim($_POST['action']);
	if($do == "checkpasswd" || $action=="checkpasswd"){
		pb_submit_check('oldpass');
		$OldPassCheck = $member->checkUserPasswdById($_POST['oldpass'], $the_memberid);
		if ($OldPassCheck>0) {
			$vals = array();
			$vals['userpass'] = $member->authPasswd(trim($_POST['newpass']));
			if (!empty($_POST['question']) && !empty($_POST['answer'])) {
				$vals['question'] = $_POST['question'];
				$vals['answer'] = $_POST['answer'];
			}
			$result = $member->save($vals, "update", $the_memberid);
			$passport->ucSingleUpdatePwd($the_membername, trim($_POST['newpass']));
			flash("success");
		}else {
			flash('old_pwd_error');
		}
	}
}
vtemplate("changepass");
?>