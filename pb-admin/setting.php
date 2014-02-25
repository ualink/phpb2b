<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2234 $
 */
require("../configs/config.inc.php");
require("../libraries/common.inc.php");
require("session_cp.inc.php");
require(LIB_PATH. "cache.class.php");
require(CLASS_PATH. "string.class.php");
//require(CACHE_LANG_PATH.'lang_emails.php');
uses("setting","typeoption","attachment");
$cache = new Caches();
$attachment_controller = new Attachment();
$typeoption = new Typeoption();
$string = new Strings();
$setting = new Settings();
setvar("AskAction", $typeoption->get_cache_type("common_option"));
$tpl_file = "setting.basic";
$item = $setting->getValues();
if (preg_match("/iis/", strtolower(pb_getenv("SERVER_SOFTWARE")))){
	$is_iis = true;
}
if (isset($_POST['do'])) {
	$do = trim($_POST['do']);
	switch ($do) {
		case "testemail":
			require(LIB_PATH. 'sendmail.inc.php');
			if (!empty($_POST['data']['setting']['testemail'])) {
				$sended = pb_sendmail(array($_POST['data']['setting']['testemail'], $_POST['data']['setting']['testemail']), L("dear_user", "tpl"), null, L("a_test_email_delete", "tpl", $G['setting']['site_name']));
				if (!$sended) {
					flash("email_sended_false");
				}else{
					flash("email_sended_success");
				}
			}else{
				$tpl_file = "setting.email";
			}
			break;
		default:
			break;
	}
}
if (isset($_GET['do'])) {
	$do = trim($_GET['do']);
	switch ($do) {
		case "basic":
			//set multi
			$tpl_file = "setting.basic";
			break;
		case "basic_desc":
			$tpl_file = "setting.basic.desc";
			break;
		case "attach":
			require(LIB_PATH. "file.class.php");
			$folder = new Files();
			if (!empty($G['setting']['thumb_small'])) {
				list($item['small_width'], $item['small_height']) = explode($attachment_controller->seperator, $G['setting']['thumb_small']);
			}else{
				list($item['small_width'], $item['small_height']) = explode($attachment_controller->seperator,$attachment_controller->small_scale);
			}
			if (!empty($G['setting']['thumb_middle'])) {
				list($item['middle_width'], $item['middle_height']) = explode($attachment_controller->seperator, $G['setting']['thumb_middle']);
			}else{
				list($item['middle_width'], $item['middle_height']) = explode($attachment_controller->seperator,$attachment_controller->middle_scale);
			}
			if (!empty($G['setting']['thumb_large'])) {
				list($item['large_width'], $item['large_height']) = explode($attachment_controller->seperator, $G['setting']['thumb_large']);
			}else{
				list($item['large_width'], $item['large_height']) = explode($attachment_controller->seperator,$attachment_controller->large_scale);
			}
			$face_list = $folder->getFiles(DATA_PATH. "fonts".DS);
			if (!empty($face_list)) {
				foreach ($face_list as $val) {
					$tmp_arr[$val['name']] = "data/fonts/".$val['name'];
				}
				setvar("DefaultFace", $folder->fontFace);
				setvar("FontFaces", $tmp_arr);
			}
			$tpl_file = "setting.attach";
			break;
		case "basic_contact":
			$tpl_file = "setting.basic.contact";
			break;
		case "datetime":
			if (isset($item['DATE_FORMAT'])) {
				$tmp_str = explode("-", $item['DATE_FORMAT']);
				$tmp_arr = array();
				foreach ($tmp_str as $key=>$val) {
					$tmp_arr[] = "%".$val;
				}
				$item['DATE_FORMAT'] = implode("-", $tmp_arr);
			}
			$tpl_file = "setting.datetime";
			break;
		case "auth":
			$tpl_file = "setting.auth";
			break;
		case "secure":
			$tpl_file = "setting.auth.secure";
			break;
		case "cache":
			$tpl_file = "setting.cache";
			break;
		case "permission":
			$tpl_file = "setting.permission";
			break;
		case "email":
			$tpl_file = "setting.email";
			break;
		case "functions":
			if($subdomain_support){
				$item['subdomain_support'] = 1;
				$item['subdomain'] = $subdomain_support;
			}
			$item['topleveldomain_support'] = ($topleveldomain_support)?1:0;
			$item['rewrite_able'] = ($rewrite_able)?1:0;
			$item['rewrite_compatible'] = ($rewrite_compatible)?1:0;
			$tpl_file = "setting.functions";
			break;
		case "register":
			$words = $pdb->GetArray("SELECT * FROM {$tb_prefix}words");
			if (!empty($words)) {
				foreach ($words as $word_val) {
					if(!empty($word_val['title'])) $tmp_str[] = $word_val['title'];
				}
				$item['forbid_word'] = implode("\r\n", $tmp_str);
			}
			$ips = $pdb->GetArray("SELECT CONCAT(ip1,'.',ip2,'.',ip3,'.',ip4) AS ip FROM {$tb_prefix}banned");
			if (!empty($ips)) {
				foreach ($ips as $ip_val) {
					if(!empty($ip_val['ip'])) $tmp_ip[] = $ip_val['ip'];
				}
				$item['forbid_ip'] = implode("\r\n", $tmp_ip);
			}
			if (empty($item['agreement']) && file_exists(CACHE_COMMON_PATH. "cache_agreement.php")) {
				$item['agreement'] = @file_get_contents(CACHE_COMMON_PATH. "cache_agreement.php");
			}
			$tpl_file = "setting.register";
			break;
		case "registerfile":
			$tpl_file = "setting.register.file";
			break;
		default:
			break;
	}
}
function edit_config($configs) {
	global $dbcharset;
	if (!is_array($configs)) {
		return;
	}
	extract($configs);
	$configfile = PHPB2B_ROOT. 'configs'.DS.'config.inc.php';
	$configfiles = file_get_contents($configfile);
	$configfiles = trim($configfiles);
	$configfiles = substr($configfiles, -2) == '?>' ? substr($configfiles, 0, -2) : $configfiles;
	$configfiles = preg_replace("/[$]absolute_uri\s*\=\s*[\"'].*?[\"'];/is", "\$absolute_uri = '".$absolute_uri."';", $configfiles);
	if(file_put_contents($configfile, $configfiles)){
		return true;
	}else{
		return false;
	}
}
if (isset($_POST['savebasic'])) {
        $sp_search = array('\\\"', "\\\'", "'");
        $sp_replace = array('&amp;', '&quot;', '&#39;');
        if (!empty($_POST['data']['setting']['site_url']) && substr($_POST['data']['setting']['site_url'], -1, 1)!='/') {
        	$_POST['data']['setting']['site_url'].="/";
        }
        $_POST['data']['setting']['site_description'] = pb_lang_merge($_POST['data']['multita']);
        if (!empty($_POST['data']['setting']['site_description'])) {
                $_POST['data']['setting']['site_description'] = str_replace($sp_search, $sp_replace, $_POST['data']['setting']['site_description']);
        }
        if (!empty($_POST['data']['setting'])) {
                $updated = $setting->replace($_POST['data']['setting']);
                if($updated) $cache->writeCache("setting", "setting");
        }
        if($updated){
                if (!empty($_POST['data']['setting']['site_url']) && (!pb_strcomp($_POST['data']['setting']['site_url'], $absolute_uri))) {
                        edit_config(array("absolute_uri"=>$_POST['data']['setting']['site_url']));
                }
                flash("success", "setting.php?do=basic");
        }else{
                flash();
        }
}
if (isset($_POST['saveauth'])) {
	$updated = $setting->replace($_POST['data']['setting']);
	if($updated){
		$cache->writeCache("setting", "setting");
		pheader("location:setting.php?do=auth");
	}
}
if (isset($_POST['save_auth_secure'])) {
	$updated = $setting->replace($_POST['data']['setting']);
	if($updated){
		$cache->writeCache("setting", "setting");
		pheader("location:setting.php?do=secure");
	}
}
if (isset($_POST['save_attach'])) {
	$vals = $_POST['data']['setting'];
	if (!empty($_POST['data']['small_width']) && !empty($_POST['data']['small_height'])) {
		$vals['thumb_small'] = $_POST['data']['small_width']."*".$_POST['data']['small_height'];
	}
	if (!empty($_POST['data']['middle_width']) && !empty($_POST['data']['middle_height'])) {
		$vals['thumb_middle'] = $_POST['data']['middle_width']."*".$_POST['data']['middle_height'];
	}
	if (!empty($_POST['data']['large_width']) && !empty($_POST['data']['large_height'])) {
		$vals['thumb_large'] = $_POST['data']['large_width']."*".$_POST['data']['large_height'];
	}
	$updated = $setting->replace($vals);
	if($updated){
		$cache->writeCache("setting", "setting");
		pheader("location:setting.php?do=attach");
	}
}
if (isset($_POST['save_datetime'])) {
	$vals = array();
	if (isset($_POST['data']['time_offset'])) {
		$vals['time_offset'] = intval($_POST['data']['time_offset']);
	}
	if (isset($_POST['data']['date_format'])) {
		$vals['date_format'] = str_replace("%", "", $_POST['data']['date_format']);
	}
	$updated = $setting->replace($vals);
	if($updated){
		$cache->writeCache("setting", "setting");
		pheader("location:setting.php?do=datetime");
	}
}
if (isset($_POST['saveregisterfile'])) {
	$updated = false;
	$updated = $setting->replace($_POST['data']['setting']);
	if($updated){
		$cache->writeCache("setting", "setting");
		pheader("location:setting.php?do=registerfile");
	}else {
		flash();
	}
}
if (isset($_POST['saveregister'])) {
	$updated = false;
	if (isset($_POST['data']['setting']['register_type']) && $_POST['data']['setting']['register_type']!="close_register") {
		if (!empty($_POST['data']['setting']['reg_filename']) && !pb_strcomp($_POST['data']['setting']['reg_filename'],$_POST['data']['reg_filename'])) {
		    $renameResult = rename(PHPB2B_ROOT. 'register.php', PHPB2B_ROOT.$_POST['data']['setting']['reg_filename']);
		}
		if (!empty($_POST['data']['setting']['post_filename']) && !pb_strcomp($_POST['data']['setting']['post_filename'],$_POST['data']['post_filename'])) {
		    $renameResult = rename(PHPB2B_ROOT. 'post.php', PHPB2B_ROOT. $_POST['data']['setting']['post_filename']);
		}		
	}
	if (!empty($_POST['data']['forbid_ip'])) {
		$datas = $string->txt2array($_POST['data']['forbid_ip']);
		if (!empty($datas)) {
			foreach ($datas as $val) {
				list($ip1, $ip2, $ip3, $ip4) = explode(".", $val);
				$tmp_ip[] = "('".$ip1."','".$ip2."','".$ip3."','".$ip4."')";
			}
			$values = implode(",", $tmp_ip);
			if (!empty($tmp_ip)) {
				$pdb->Execute("INSERT INTO {$tb_prefix}banned (ip1,ip2,ip3,ip4) VALUES ".$values);
			}
		}
	}
	if (!empty($_POST['data']['forbid_word'])) {
		$datas = $string->txt2array($_POST['data']['forbid_word']);
		if (!empty($datas)) {
			$cache->writeCache("words", "words", "\$_PB_CACHE['words'] = ".$cache->evalArray($datas));
			foreach ($datas as $val) {
				list($wd1, $wd2) = explode("=", $val);
				$tmp_word[] = "('".$wd1."','".$wd2."')";
			}
			$values = implode(",", $tmp_word);
			if (!empty($values)) {
				$pdb->Execute("INSERT INTO {$tb_prefix}words (title,replace_to) VALUES ".$values);
			}
		}
	}
	if ($_POST['data']['setting']['welcome_msg']==1 || $_POST['data']['setting']['welcome_msg']==2) {
		if (!empty($_POST['data']['welcome_msg_title'])) {
			$_POST['data']['setting']['welcome_msg_title'] = $_POST['data']['welcome_msg_title'];
		}
		if (!empty($_POST['data']['welcome_msg_content'])) {
			$_POST['data']['setting']['welcome_msg_content'] = $_POST['data']['welcome_msg_content'];
		}
	}
	//$updated = $setting->replace($_POST['data']['setting1'], 1);
	$updated = $setting->replace($_POST['data']['setting']);
	if($updated){
		$cache->writeCache("setting", "setting");
		//$cache->writeCache("setting1", "setting1");
		pheader("location:setting.php?do=register");
	}else {
		flash();
	}
}
if (isset($_POST['save_cache'])) {
	$updated = $setting->replace($_POST['data']['setting']);
	if($updated){
		$cache->writeCache("setting", "setting");
		pheader("location:setting.php?do=cache");
	}
}
if (isset($_POST['save_mail'])) {
	$updated = $setting->replace($_POST['data']['setting']);
	if($updated){
		$cache->writeCache("setting", "setting");
		pheader("location:setting.php?do=email");
	}
}
function edit_function($data){
	if (empty($data) && !is_array($data)) {
		return;
	}
	$configfile = PHPB2B_ROOT. 'configs'.DS.'config.inc.php';
	$configfiles = file_get_contents($configfile);
	$configfiles = trim($configfiles);
	foreach($data as $key=>$val){
		$pattern[$key] = "/[$]".$key."\s*\=\s*.*?;/is";
		$replacement[$key] = "\$".$key." = ".$val.";";
		if ($key == "subdomain_support") {
			if ($val==1) {
				$replacement[$key] = "\$".$key." = '".$data['subdomain']."';";
			}else{
				$replacement[$key] = "\$".$key." = ".$val.";";
			}
		}
	}
	$configfiles = preg_replace($pattern, $replacement , $configfiles);
	if(file_put_contents($configfile, $configfiles)){
		return true;
	}else{
		return false;
	}
}
if (isset($_POST['save_functions'])) {
	$rs = ''; 
	$data = $_POST['data'];
	$example_dir = DATA_PATH.'examples'.DS;
	if($data['rewrite_able']==1){
		if ($is_iis) {
			$rewrite_file = $example_dir.'_httpd.ini';
			copy($example_dir.'_httpd.ini', PHPB2B_ROOT.'httpd.ini');
		}else{
			$rewrite_file = $example_dir.'_.htaccess';
			$file = file_get_contents($rewrite_file);
//			$pattern = "/(http){1}\:\/\/[w]{3}[\.]yourdomain[\.]com[\/]/";
//			$replacement = URL;
//			$file = preg_replace($pattern,$replacement,$files);
			file_put_contents(PHPB2B_ROOT.'.htaccess',$file);
		}
	}else{
		@unlink(PHPB2B_ROOT.'.htaccess');
		@unlink(PHPB2B_ROOT.'httpd.ini');
	}
	if($data['subdomain_support']==1 && $data['subdomain']!=''){
		$subdomain = $data['subdomain'];
		if(file_exists(PHPB2B_ROOT.'.htaccess')){
			$rewrite_file = PHPB2B_ROOT.'.htaccess';
		}else{
			$rewrite_file = $example_dir.'_.htaccess';
		}
		$files = file_get_contents($rewrite_file);
		$pattern = "/[\.]yourdomain[\.]com/";
		$replacement = $subdomain;
		$file = preg_replace($pattern,$replacement, $files);
		file_put_contents(PHPB2B_ROOT.'.htaccess', $file);
	}
	$updated = edit_function($data);
	if($updated){
		flash("success");
	}else{
		flash();
	}
}
setvar("item", $item);
template($tpl_file);
?>