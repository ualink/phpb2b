<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
define('CURSCRIPT', 'templet');
require("../libraries/common.inc.php");
uses("templet","typeoption","setting");
require("session_cp.inc.php");
require(LIB_PATH. "cache.class.php");
$G['membergroup'] = cache_read("membergroup");
require(CACHE_COMMON_PATH."cache_type.php");
$cache = new Caches();
$setting = new Settings();
$templet = new Templets();
$typeoption = new Typeoption();
$templet_controller = new Templet();
$conditions = null;
$tpl_file = "templet";
setvar("AskAction", $typeoption->get_cache_type("common_option"));
if (isset($_POST['save']) && !empty($_POST['data']['templet']['title'])) {
	$vals = array();
	$vals = $_POST['data']['templet'];
	if(isset($_POST['data']['require_membertype']) && !in_array(0, $_POST['data']['require_membertype']) && !empty($_POST['data']['require_membertype'])){
		$res = "[".implode("][", $_POST['data']['require_membertype'])."]";
		$vals['require_membertype'] = $res;
	}elseif(!empty($_POST['data']['require_membertype'])){
		$vals['require_membertype'] = 0;
	}
	if(isset($_POST['data']['require_membergroups']) && !in_array(0, $_POST['data']['require_membergroups']) && !empty($_POST['data']['require_membergroups'])){
		$res = "[".implode("][", $_POST['data']['require_membergroups'])."]";
		$vals['require_membergroups'] = $res;
	}elseif(!empty($_POST['data']['require_membergroups'])){
		$vals['require_membergroups'] = 0;
	}
	if (isset($_POST['id'])) {
		$id = intval($_POST['id']);
	}
	$type = trim($_POST['type']);
	if (empty($type)) {
		$type = 'system';
	}
	if ($type=="user") {
		//description for styles column 20120920
		$vals['description'] = serialize($_POST['data']['skins']);
	}
	if(!empty($id)){
		$result = $templet->save($vals, "update", $id);
		if (isset($_POST['data']['templet']['is_default']) && $_POST['data']['templet']['is_default']==1) {
			$pdb->Execute("UPDATE {$tb_prefix}templets SET is_default='0' WHERE type='".$type."'");
			$pdb->Execute("UPDATE {$tb_prefix}templets SET is_default='1' WHERE id='".$_POST['id']."'");
		}
	}else{
		$result = $templet->save($vals);
	}
	if (!empty($_POST['data']['site_style'])) {
		$the_style = trim($_POST['data']['site_style']);
		$setting->replace(array("site_style"=>$the_style));
		$site_theme_styles = $templet_controller->getStyle();
		$setting->replace(array("site_theme_styles"=>serialize($site_theme_styles)));
//		$setting->replace(array("site_theme_styles"=>serialize($_POST['data']['site_theme_styles'])));
		$result = $cache->writeCache("setting", "setting");
	}
	if(!$result){
		flash();
	}else{
		flash("success", "templet.php?type=".$_POST['type']);
	}
}
if(isset($_GET['do'])){
	$do = trim($_GET['do']);
	if (!empty($_GET['id'])) {
		$id = intval($_GET['id']);
	}
	if ($do == "uninstall" && !empty($id)) {
		$templet->del($id);
	}
	if ($do == "install" && !empty($_GET['entry'])) {
		$entry = trim($_GET['entry']);
		$templet_controller->install($entry);
		flash("tpl_installed_ok", "templet.php?type=".$_GET['type']);
	}
	if ($do == "setup" && !empty($_GET['name']) && ($pdb->GetRow("SELECT * FROM {$tb_prefix}templets WHERE id=".$id))) {
		$the_theme = trim($_GET['name']);
		$setting->replace(array("site_theme"=>$the_theme));
		$result = $cache->writeCache("setting", "setting");
		if ($result) {
			$templet->exchangeDefault($id);
			flash("success", "templet.php?type=system");
		}else{
			flash();
		}
	}
	if($do == "edit"){
		setvar("CurrentSiteThemeStyle", $G['setting']['site_style']);
		$info = $templet->read("*", $id);
		$site_theme = '';
		if(is_dir(PHPB2B_ROOT. $templet_controller->system_skin_dir.DS.$info['name'].DS)) {
			$site_theme = $info['name'];
		}
		$site_styles = $templet_controller->getStyle($site_theme);
		setvar("SiteStyles", $site_styles);
		if (!empty($id)) {
			setvar("item",$info);
		}
		if ($info['type']=='user') {
			require(LIB_PATH. "file.class.php");
			$file = new Files();
			if(strpos($info['directory'], 'templates')===false){
				$info['directory'] = 'templates/'.$info['directory'];
			}
			$_user_skins = $file->getFolders(PHPB2B_ROOT. $info['directory']. "styles".DS);
			$user_skins = array();
			foreach ($_user_skins as $key=>$val) {
				$skins_file = PHPB2B_ROOT. $info['directory']. "styles".DS.$val['name'].DS."style.css";
				if (is_file($skins_file)) {
					$skins_data = $templet_controller->getSkinData($skins_file);
					if (isset($skins_data['Name'])) {
						$user_skins[$val['name']] = $skins_data['Name'];
					}else{
						$user_skins[$val['name']] = $val['name'];
					}
				}
			}
			setvar("UserSkins", $user_skins);
		}
		$user_types = array();
		foreach ($G['membergroup'] as $key=>$val) {
			$user_types[$key] = $val['name'];
		}
		setvar("Membergroups", $user_types);
		setvar("Membertypes", $_PB_CACHE['membertype']);
		$tpl_file = "templet.edit";
		template($tpl_file);
		exit;
	}
}
if (isset($_POST['del']) && is_array($_POST['id'])) {
	$ids = array_filter($_POST['id']);
	$result = $templet->del($ids);
}

if (isset($_POST['install']) && is_array($_POST['id'])) {
	for ($i=0; $i<count($_POST['id']); $i++) {
		$entry = $_POST['entry'][$i];
		if(!empty($entry))
		$templet_controller->install($entry);
	}
	flash("tpl_installed_ok", "templet.php?type=".$_POST['type']);
}
$result = $templet_controller->getTemplate();
foreach ($result as $key=>$val) {
	if($val['type'] == 'user') $val['directory'] = 'templates/'.$val['directory'];
	if (!is_dir(PHPB2B_ROOT.$val['directory'])) {
		unset($result[$key]);
	}
}
setvar("Items", $result);
template("templet");
?>