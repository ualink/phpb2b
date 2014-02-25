<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2234 $
 */
require("../libraries/common.inc.php");
require("session_cp.inc.php");
require(LIB_PATH. "file.class.php");
uses("brand");
$brand = new Brands();
require(LIB_PATH. "cache.class.php");
$cache = new Caches();
$tpl_file = "htmlcache";
if (isset($_POST['do'])) {
	$do = trim($_POST['do']);
	$file = new Files();
	$cache->writeCache("javascript", "javascript");
	switch ($do) {
		case "clear":
			if (in_array("membercache", $_POST['data']['type'])) {
				$pdb->Execute("TRUNCATE `{$tb_prefix}membercaches`");
				$pdb->Execute("TRUNCATE `{$tb_prefix}spacecaches`");
			}
			if (in_array("spacecache", $_POST['data']['type'])) {
				$pdb->Execute("TRUNCATE `{$tb_prefix}spacecaches`");
			}
			if (in_array("smartycache", $_POST['data']['type'])) {
				$smarty->clearAllCache();
			}
			if (in_array("smartycompile", $_POST['data']['type'])) {
				$smarty->clearCompiledTemplate();
				$file->rmDirs(DATA_PATH. "templates_c");
			}
			if (in_array("htmlcache", $_POST['data']['type'])) {
				$file->rmDirs(DATA_PATH. 'archiver');
			}
			if (in_array("dbcache", $_POST['data']['type'])) {
				$file->exclude[] = "index.htm";
				$file->rmDirs(DATA_PATH. "dbcache", false, false);
				$file->rmDirs(DATA_PATH. "dbcache", false, true);
			}
			flash("success", "htmlcache.php?do=clear");
			break;
		case "update":
			if (in_array("area", $_POST['data']['type'])) {
				$cache->writeCache("area", "area");
				$cache->writeCache("country", "country");
			}
			if (in_array("options", $_POST['data']['type'])) {
				$cache->updateTypevars();
			}
			if (in_array("industry", $_POST['data']['type'])) {
				uses("industry");
				$industry = new Industries();
				$industry->updateCache();
				$cache->writeCache("industry", "industry");
			}
			if (in_array("language", $_POST['data']['type'])) {
				$cache->updateLanguages();
				$cache->writeCache("javascript", "javascript");
			}
			if (in_array("setting", $_POST['data']['type'])) {
				$cache->updateIndexCache();
				$cache->updateTypes();
				$cache->writeCache("setting", "setting");
				$cache->writeCache("nav", "nav");
				$cache->writeCache("javascript", "javascript");
			}
			if (in_array("ext", $_POST['data']['type'])) {
				$cache->writeCache("userpage", "userpage");
				$cache->writeCache("trusttype", "trusttype");
				$cache->writeCache("membergroup", "membergroup");
				$cache->writeCache("form", "form");
			}
			flash("success", "htmlcache.php?do=update");
			break;
		default:
			break;
	}
}
template($tpl_file);
?>