<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2289 $
 */
session_start();
define('CURSCRIPT', 'ajax');
define('NOROBOT', TRUE);
require_once 'libraries/common.inc.php';
require("share.inc.php");
$return = array();
$result = array();
$post_actions = array("checkpasswd");
$get_actions = array("checkusername");
uses("member", "company");
$member = new Members();
$company = new Companies();
/**
 * return to ajax for the executed result
 *
 * @param Array $data
 */
if( !function_exists('ajax_exit') ){
	function ajax_exit($data)
	{
		echo trim(json_encode($data));
		exit;
	}
}

//file.name.ext, returns file.name
function get_pathinfo($file) { 
    if (defined('PATHINFO_FILENAME')) {
		return pathinfo($file);
	}
    if (strstr($file, '.')) return array('filename'=>substr($file,0,strrpos($file,'.')), 'extension'=>end(explode(".", $file)));
}
if (isset($_POST['do_action'])) {
	$action = trim($_POST['do_action']);
	switch ($action) {
		case "uploadify":
			uses("attachment");
			//check permission
			$fileElementName = 'Filedata';
			$attachment = new Attachment($fileElementName);
			$authed = false;
			$attachment->if_thumb_large = false;
			if(!empty($_COOKIE[$cookiepre.'admin'])){
				$tAdminInfo = authcode($_COOKIE[$cookiepre.'admin'], "DECODE");
				$tAdminInfo = explode("\n", $tAdminInfo);
				if (!empty($tAdminInfo)) {
					$authed = true;
				}
			}
			$targetPath = PHPB2B_ROOT. $attachment->attachment_dir.DS."swfupload".DS.gmdate("Y").gmdate("m").DS.gmdate("d").DS;
			if (!is_dir($targetPath)) {
				pb_create_folder($targetPath);
			}
			$orignal_fileinfo = get_pathinfo($_FILES[$fileElementName]['name']);
			$new_file_name = gmdate("His").pb_radom().".".$orignal_fileinfo['extension'];
			$targetFile =  str_replace('//','/',$targetPath) . $new_file_name;
			if($authed && is_uploaded_file($_FILES[$fileElementName]['tmp_name'])) {
				move_uploaded_file($_FILES[$fileElementName]['tmp_name'],$targetFile);
				$return['url'] = $absolute_uri.$attachment_url."swfupload/".gmdate("Y").gmdate("m")."/".gmdate("d")."/".$new_file_name;
				$return['name'] = $_FILES[$fileElementName]['name'];
				ajax_exit($return);
			}
			break;
		case "attachment":
			uses("attachment");
			//check permission
			$fileElementName = 'fileToUpload';
			$attachment = new Attachment($fileElementName);
			$authed = false;
			if(!empty($_COOKIE[$cookiepre.'admin'])){
				$tAdminInfo = authcode($_COOKIE[$cookiepre.'admin'], "DECODE");
				$tAdminInfo = explode("\n", $tAdminInfo);
				if (!empty($tAdminInfo)) {
					$authed = true;
				}
			}
			if (!empty($pb_user['pb_userid'])) {
				//if logined, check upload limit
				$attachment->is_image = true;
				$authed = true;
			}
			$attachment->if_thumb_large = false;
			$attachment->upload_dir = "swfupload".DS.gmdate("Y").gmdate("m").DS.gmdate("d");
			$str = $_FILES[$fileElementName]['name'];
			if (!empty($_FILES[$fileElementName]['name']) && $authed) {
				$attachment->if_thumb = false;
				$attachment->if_watermark = false;
				$attachment->rename_file = date("Hi").pb_radom();
				$attachment->upload_process();
				if (empty($attachment->file_full_url)) {
					$return["error"] = $_FILES[$fileElementName]['error'];
					$return['msg'] = L("action_failed").":".$_FILES[$fileElementName]['error'];
				}else{
					$return["error"] = '';
					$return['file_url'] = $absolute_uri.$attachment_dir."/".$attachment->file_full_url;
					$return['msg'] = L("action_successfully");
					$return['title'] = $_FILES[$fileElementName]['name'];
				}
			}else{
				$return["error"] = L("no_perm");
				$return['msg'] = L("access_denied");
			}
			ajax_exit($return);
			break;
		default:
			break;
	}
}
if (isset($_GET['action'])) {
	$action = trim($_GET['action']);
	switch ($action) {
		case "selection":
			$result = array();
			if (in_array($_GET['module'], array("industry", "area"))) {
				$sql = "SELECT id AS region_id,name AS region_name FROM ".$tb_prefix.PbController::pluralize($_GET['module'])." WHERE parent_id='".intval($_GET['parent_id'])."' AND available=1";
				$result = $pdb->GetArray($sql);
				for($i=0; $i<count($result); $i++){
					$result[$i]['region_name'] = pb_lang_split($result[$i]['region_name']);
				}
			}
			ajax_exit($result);
			break;
		case "checkusername":
			if(isset($_GET['username'])) {
				$result = call_user_func_array($action, array($_GET['username']));		
				if($result == true){
					$return["isError"] = 1;
				}else{
					$return["isError"] = 0;
				}
			}
			ajax_exit($return);
			break;
		case "addtag":
			break;
		case "showLoginBar":
			if($pb_user){
				$output = '<em>'.L('hello','tpl').$pb_userinfo['pb_username'].'</em>
        <a href="redirect.php?url=/virtual-office">['.L('my_office_room','tpl').']</a>';
		        if($pb_userinfo['is_admin']) $output.'=<a  href="pb-admin/login.php" target="_blank">['.L('control_pannel','tpl').']</a>';
        $output.='<a href="logging.php?action=logout">['.L('login_out','tpl').']</a>';
        		die($output);
			}else{
				die('<em>'.L('hello_welcome_to', 'tpl').$G['setting']['site_name'].'</em>
        <a href="logging.php">&nbsp;['.L('pls_login', 'tpl').']</a>
        <a href="member.php" title="register" ><strong>['.L('free','tpl').L('register','tpl').']</strong></a>');
			}
			break;
		case "checkemail":
			if(isset($_GET['email'])) {
				$result = call_user_func_array($action, array($_GET['email']));
				if($result){
					$return["isError"] = 1;
				}else{
					if (!pb_check_email($_GET['email'])) {
						$return["isError"] = 2;
					}else{
						$return["isError"] = 0;
					}
				}
			}
			ajax_exit($return);
			break;
	}
}

function checkusername($input_username)
{
	global $member;
	return $member->checkUserExist($input_username, false);
}

function checkemail($email)
{
	global $member;
	return $member->checkUserExistsByEmail($email);
}

function checkcompanyname($company_name)
{
	global $company;
	return $company->checkNameExists($company_name);
}
die(L("invalid_request"));
?>