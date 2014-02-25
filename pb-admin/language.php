<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
require("session_cp.inc.php");
require(LIB_PATH. "cache.class.php");
require(LIB_PATH. "file.class.php");
uses("setting");
$setting_controller = new Setting();
$setting = new Settings();
$cache = new Caches();
$file = new Files();
$conditions = null;
$tpl_file = "language";
if (isset($_POST['action'])) {
	$vals = $datas = array();
	foreach ($_POST['data']['item'] as $key=>$val) {
		$_POST['data']['language'][$val]['img'] = str_replace(array("../", "../../"), "", $_POST['data']['language'][$val]['img']);
		$vals[$val] = $_POST['data']['language'][$val];
	}
	$datas['languages'] = serialize($vals);
	$setting->replace($datas);
	if(isset($_POST['update_dot'])){
		foreach ($vals as $lang=>$lang_var) {
			pb_configmake($lang, false);
			$cache->lang_dirname = $lang;
			$cache->cacheAll();
		}
	}else{
		pb_configmake($app_lang, false);
		$cache->updateLanguages();
	}
	flash("success");
}
$result = $file->getFolders("../languages/");
$items = array();
$installed_languages = array();
if (!empty($G['setting']['languages'])) {
	$installed_languages = unserialize($G['setting']['languages']);
}
if (!empty($result)) {
	foreach ($result as $key=>$val) {
		if(file_exists($templet_file = PHPB2B_ROOT."languages/".$val['name']."/readme.txt")){
			$data = $setting_controller->getSkinData($templet_file);
			$name = $val['name'];
			$title = $data['Name'];
			if ($charset == "gbk") {
				//only for gbk chinese convert
				$title = iconv('gbk', $charset, $title);
			}
			$items[$val['name']]['name'] = $name;
			$items[$val['name']]['title'] = $title;
			$files=glob('../languages/'.$name.'/icon*')?glob('../languages/'.$name.'/icon*'):array();
			if (!empty($files)) {
				$items[$val['name']]['img'] = $files[0];
			}else{
				$items[$val['name']]['img'] = '';
			}
			$items[$val['name']]['available'] = array_key_exists($val['name'], $installed_languages)?1:0;
		}
	}
}
setvar("Items", $items);
template($tpl_file);
?>