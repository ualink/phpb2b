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
require(CACHE_COMMON_PATH."cache_type.php");
$cache = new Caches();
$tpl_file = "companytype";
if (isset($_POST['do'])) {
	$do = trim($_POST['do']);
	if ($do == "save") {
		$ins_arr = array();
		$tmp_arr = explode("\r\n", $_POST['data']['sort']);
		array_filter($tmp_arr);
		$i = 1;
		foreach ($tmp_arr as $key=>$val) {
			$ins_arr[$i] = "(".$i.",'".$val."')";
			$i++;
		}
		if (!empty($ins_arr)) {
			$pdb->Execute("TRUNCATE TABLE {$tb_prefix}companytypes");
			$ins_str = "REPLACE INTO {$tb_prefix}companytypes (id,name) VALUES ".implode(",", $ins_arr).";";
			$pdb->Execute($ins_str);
		}		
		if($cache->updateTypes()){
			flash("success");
		}else{
			flash();
		}
	}
}
if (!empty($_PB_CACHE['companytype'])) {
	setvar("sorts", implode("\r\n", $_PB_CACHE['companytype']));
}
template($tpl_file);
?>