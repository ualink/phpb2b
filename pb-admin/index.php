<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
define('CURSCRIPT', 'index');
require("../libraries/common.inc.php");
if(empty($_COOKIE[$cookiepre.'admin']) || !($_COOKIE[$cookiepre.'admin'])){
	pheader("location:login.php");
}
require("session_cp.inc.php");
require("menu.php");
if (!empty($adminer->info['permissions']) && $adminer->info['member_id']!=$administrator_id) {
	$allowed_permissions = explode(",", $adminer->info['permissions']);
	foreach ($menus as $key=>$val) {
		if (!in_array($key, $allowed_permissions)) {
			unset($menus[$key]);
		}else{
			foreach ($val['children'] as $key1=>$val1) {
				if (!in_array($key1, $allowed_permissions)) {
					unset($menus[$key]['children'][$key1]);
				}
			}
		}
	}
}
$smarty->template_dir = PHPB2B_ROOT. "templates/admin/";
if ($charset!="utf-8") {
	$menus = iconv_all($charset, "utf-8", $menus);
}
function iconv_all($in_charset,$out_charset,$in)
{
    if(is_string($in))
    {
        $in=iconv($in_charset,$out_charset,$in);
    }
    elseif(is_array($in))
    {
        foreach($in as $key=>$value)
        {
            $in[$key]=iconv_all($in_charset,$out_charset,$value);
        }
    }
    elseif(is_object($in))
    {
        foreach($in as $key=>$value)
        {
            $in->$key=iconv_all($in_charset,$out_charset,$value);
        }
    }
 
    return $in;
}
$smarty->assign("ActionMenus", json_encode($menus));
$tpl_file = "index";
template($tpl_file);
?>