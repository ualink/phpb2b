<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2149 $
 */
require("../libraries/common.inc.php");
require("session_cp.inc.php");
require(LIB_PATH. "payment.class.php");
require(LIB_PATH. "file.class.php");
require(LIB_PATH. "cache.class.php");
uses("setting", "typeoption");
$cache = new Caches();
$typeoption = new Typeoption();
$file = new Files();
$payment = new Payments();
$pay_controller = new PbController();
$setting = new Settings();
$tpl_file = "payment";
setvar("AskAction", $typeoption->get_cache_type("common_option"));
$result = $payment->getpayments();
setvar("Items", $result);
setvar("payment_url", URL.'plugins/'.$payment->payment_dir.'/');
$item = $setting->getValues(1);
if (isset($_POST['save'])) {
	$datas = $_POST['data']['payment'];
	if (isset($_POST['id'])) {
		$id = intval($_POST['id']);
	}
	if (!empty($_POST['data']['config'])) {
		$datas['config'] = serialize($_POST['data']['config']);
	}
	if (!empty($id)) {
		$result = $pdb->Execute("UPDATE {$tb_prefix}payments SET title='".$datas['title']."',description='".$datas['description']."',if_online_support='".$datas['if_online_support']."',available='".$datas['available']."',config='".$datas['config']."',modified={$time_stamp} WHERE id=".$id);
	}else{
		$result = $pdb->Execute("INSERT INTO {$tb_prefix}payments (name,title,description,available,config,created,modified) VALUE ('".$datas['name']."','".$datas['title']."','".$datas['description']."','".$datas['available']."','".$datas['config']."',{$time_stamp},{$time_stamp});");
	}
	if (!$result) {
		flash("action_failed", null, 0);
	}else{
		flash("success", "payment.php");
	}
}
if (isset($_GET['do'])) {
	$do = trim($_GET['do']);
	if (!empty($_GET['id'])) {
		$id = intval($_GET['id']);
	}
	if ($do == "rule") {
		$tpl_file = "payment.rule";
		setvar("item", $item);
		template($tpl_file, 1);
	}
	if (!empty($_GET['entry'])) {
		$entry = trim($_GET['entry']);
		$tpl_file = "payment.setting";
		if ($do == "install") {
			$cfg = $pay_controller->getSkinData($payment->payment_path.$entry.".php");
			$item = $cfg;
			$item['name'] = $entry;
			$item['title'] = $cfg['Name'];
			$item['description'] = $cfg['Description'];
		}elseif($do == "edit" && !empty($id)){
			$result = $pdb->GetRow("SELECT * FROM {$tb_prefix}payments WHERE id=".$id);
			//get module config from plugin.
			if (!empty($result['config'])) {
				$configs = unserialize($result['config']);
				$item = array_merge($result, $configs);
				unset($result['config']);
			}else{
				$item = $result;
			}
		}
		require_once(PHPB2B_ROOT. 'plugins'.DS.'payments'.DS.$entry.".php");
		$ext_arr = $modules[$entry]['config'];
		for ($i=0; $i<count($ext_arr); $i++) {
			$ext_arr[$i]['value'] = $configs[$ext_arr[$i]['name']];
		}
		setvar("module_configs", $ext_arr);
		setvar("item", $item);
		template($tpl_file);
		exit;
	}
	if ($do == "uninstall" && !empty($id)) {
		$payment->uninstall($id);
	}
}
template($tpl_file);
?>