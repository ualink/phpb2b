<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
define('IN_PBADMIN', TRUE);
if(empty($_COOKIE[$cookiepre.'admin']) || !($_COOKIE[$cookiepre.'admin'])){
	echo "<script language='javascript'>top.location.href='login.php';</script>";
	exit;
}else{
	uses("adminfield");
	$adminer = new Adminfields();	
    $tAdminInfo = authcode($_COOKIE[$cookiepre.'admin'], "DECODE");
    $tAdminInfo = explode("\n", $tAdminInfo);
    $current_adminer_id = $tAdminInfo[0];
    $current_adminer = $tAdminInfo[1];
    $current_pass = $tAdminInfo[2];
    $adminer->loadsession($current_adminer_id, pb_get_client_ip("str"), $cfg_checkip);
    $adminer_info = $adminer->info;
    uaAssign(array("current_adminer"=>$current_adminer, "current_adminer_id"=>$current_adminer_id));
}
$sections = array('admin','message','adminmenu');
$smarty->configLoad('default.conf', $sections);
require(PHPB2B_ROOT. 'phpb2b_version.php');
$ADODB_CACHE_DIR = DATA_PATH.'dbcache';
$smarty->template_dir = PHPB2B_ROOT. "templates/admin/";
$smarty->assign("admin_theme_path", "../templates/admin/");
$smarty->setCompileDir($smarty->getCompileDir()."pb-admin".DS);
$smarty->flash_layout = "flash";
$smarty->assign("addParams", $viewhelper->addParams);
$smarty->assign("today_timestamp", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
function size_info($fileSize) {
	$size = sprintf("%u", $fileSize);
	if($size == 0) {
		return("0 Bytes");
	}
	$sizename = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
	return round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $sizename[$i];
}
?>