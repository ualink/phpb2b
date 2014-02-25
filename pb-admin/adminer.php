<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
require("session_cp.inc.php");
require(PHPB2B_ROOT.'./libraries/page.class.php');
uses("member");
$member = new Members();
$tpl_file = "adminer";
$page = new Pages();
setvar("AdministratorId", $administrator_id);
if (isset($_POST['changepass']) && !empty($_POST['data']['adminer'])) {
	$old_pass = trim($_POST['data']['old_pass']);
	if (!pb_strcomp($current_pass, md5($old_pass))) {
		flash();
	}
	$result = $adminer->updatePasswd($current_adminer_id, $_POST['data']['adminer']['user_pass']);
	if(!$result) {
		flash();
	}
}
if(isset($_POST['del']) && !empty($_POST['id'])){
	$ids = $_POST['id'];
	foreach($ids as $val){
		if (pb_strcomp($val, $current_adminer_id)||pb_strcomp($val, $administrator_id)) {
			flash();
		}else{
			$adminer->primaryKey = "member_id";
			$result = $adminer->del(intval($val));
		}
	}
}
if (isset($_POST['save'])) {
	$vals = array();
	if ($_POST['do']=="admingroup" && $_POST['action']=="edit") {
		if(isset($_POST['id'])){
			$id = intval($_POST['id']);
		}
		if (!empty($id)) {
			$result = $pdb->Execute("UPDATE ".$tb_prefix."adminroles SET name='".$_POST['data']['adminrole']['name']."' WHERE id={$id}");
		}else{
			$result = $pdb->Execute("INSERT INTO ".$tb_prefix."adminroles (name) VALUES ('".$_POST['data']['adminrole']['name']."')");
		}
		if (!$result) {
			flash();
		}else{
			flash("success", "adminer.php?do=admingroup");
		}
	}
	if(!empty($_POST['data']['adminfield'])){
		$vals = $_POST['data']['adminfield'];
		if ($_POST['auth'] == 1) {
			if (!empty($_POST['priv']) && is_array($_POST['priv'])) {
				$vals['permissions'] = implode(",", $_POST['priv']);
			}
		}else{
			$vals['permissions'] = '';
		}
		if (!empty($_POST['data']['adminer']['user_pass'])) {
			$vals['user_pass'] = $member->authPasswd($_POST['data']['adminer']['user_pass']);
		}
		$adminer->primaryKey = "member_id";
		if (!empty($_POST['data']['expired'])) {
			include(LIB_PATH. "time.class.php");
			$vals['expired'] = Times::dateConvert($_POST['data']['expired']);
		}
		if (!empty($_POST['member_id'])) {
			$member_id = intval($_POST['member_id']);
			$member->save($_POST['data']['member'], "update", $member_id);
			//update role
			$result = $adminer->save($vals, "update", $member_id);
			if(!$pdb->Execute("UPDATE {$tb_prefix}roleadminers SET adminrole_id='".$_POST['data']['adminrole_id']."' WHERE adminer_id='".$member_id."'")){
				$pdb->Execute("INSERT INTO {$tb_prefix}roleadminers (adminrole_id,adminer_id) VALUES ('".$_POST['data']['adminrole_id']."','".$member_id."')");
			}
		}else{
			//search member_id
			if (!empty($_POST['data']['username'])) {
				$sql = "SELECT id FROM {$tb_prefix}members WHERE username='".$_POST['data']['username']."'";
				$member_id = $pdb->GetOne($sql);
				if ($member_id) {
					$vals['member_id'] = $member_id;
					//add role
					$result = $adminer->save($vals);
					$pdb->Execute("INSERT INTO {$tb_prefix}roleadminers (adminrole_id,adminer_id) VALUES ('".$_POST['data']['adminrole_id']."','".$member_id."')");
				}else{
					flash("member_not_exists");
				}
			}else{
				flash();
			}
		}
		flash("success");
	}
}
if (isset($_GET['do'])) {
	$do = trim($_GET['do']);
	$action = trim($_GET['action']);
	if (!empty($_GET['id'])) {
		$id = intval($_GET['id']);
	}
	if ($do == "admingroup") {
		if ($action=="edit") {
			$tpl_file = "adminrole.edit";	
			if (!empty($id)) {
				setvar("item", $result = $pdb->GetRow("SELECT * FROM {$tb_prefix}adminroles ag WHERE id=".$id));
			}
		}else{
			setvar("Items", $result = $pdb->GetArray("SELECT * FROM {$tb_prefix}adminroles ag"));
			$tpl_file = "adminrole";
		}
		template($tpl_file);
		exit;
	}
	if ($do == "del" && !empty($id)) { 
		if (pb_strcomp($id, $current_adminer_id)||pb_strcomp($id,$administrator_id)) {
			flash();
		}else {
			$adminer->primaryKey = "member_id";
			$result = $adminer->del(intval($id));
		}
	}	
	if ($do == "profile") {
		$res = $pdb->GetRow("SELECT m.*,af.* FROM {$tb_prefix}adminfields af LEFT JOIN {$tb_prefix}members m ON m.id=af.member_id WHERE af.member_id={$current_adminer_id}");
		$res['member_id'] = $res['id'];
		setvar("item",$res);
		$tpl_file = "adminer.edit";
		template($tpl_file);
		exit;
	}
	if ($do == "edit") {
		require("menu.php");
		
		$adminrole_result = $pdb->GetArray("SELECT * FROM {$tb_prefix}adminroles ar");
		if (!empty($adminrole_result)) {
			foreach ($adminrole_result as $key=>$val) {
				$tmp_adminrole_result[$val['id']] = $val['name'];
			}
			setvar("adminrole_result", $tmp_adminrole_result);
		}
		if(!empty($id)){
			$res = $pdb->GetRow("SELECT m.*,af.* FROM {$tb_prefix}adminfields af LEFT JOIN {$tb_prefix}members m ON m.id=af.member_id WHERE af.member_id={$id}");
			$res['adminrole_id'] = $pdb->GetOne("SELECT ra.adminrole_id FROM {$tb_prefix}roleadminers ra LEFT JOIN {$tb_prefix}adminroles ar ON ra.adminrole_id=ar.id WHERE ra.adminer_id=".$id);
			if($res['expired']) $res['expire_date'] = df($res['expired']);
			$allowed_permissions = explode(",", $res['permissions']);
			foreach ($menus as $key=>$val) {
				if (in_array($key, $allowed_permissions)) {
					$menus[$key]['check'] = 1;
					foreach ($val['children'] as $key1=>$val1) {
						if (in_array($key1, $allowed_permissions)) {
							$menus[$key]['children'][$key1]['check'] = 1;
						}
					}
				}
			}
			setvar("item",$res);
		}
		setvar("Privileges", $menus);
		$tpl_file = "adminer.edit";
		template($tpl_file);
		exit;
	}
	if($do=="password"){
		$tpl_file = "adminer.password";
		template($tpl_file);
		exit;
	}
}
$adminer_result = $pdb->GetArray("SELECT m.username,af.first_name,af.last_login,af.last_ip,af.last_name,m.id,af.member_id FROM {$tb_prefix}adminfields af LEFT JOIN {$tb_prefix}members m ON m.id=af.member_id");
if (!empty($adminer_result)) {
	for($i=0; $i<count($adminer_result); $i++){
		$adminer_result[$i]['groupname'] = $pdb->GetOne("SELECT ar.name FROM {$tb_prefix}roleadminers ra LEFT JOIN {$tb_prefix}adminroles ar ON ra.adminrole_id=ar.id WHERE ra.adminer_id=".$adminer_result[$i]['member_id']);
	}
}
setvar("Items", $adminer_result);
template($tpl_file);
?>