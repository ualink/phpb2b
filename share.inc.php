<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
$theme_name = "default";//templet
$tpl_dir = "templates";
//if(is_dir(PHPB2B_ROOT. "templates/".$app_lang."/")) $theme_name = $app_lang;
//$limit = 10;//site every page set.
$pos = 0;
if (isset($_GET['pos'])) {
	$pos = intval($_GET['pos']);
}
$_G['nav'] = cache_read("nav");
setvar("navs", $_G['nav']['navs']);
if(!MAGIC_QUOTES_GPC) {
	setvar("_G", am($G['setting'], $_GET, $_SERVER));
}
$style_name = !empty($G['setting']['site_style'])?$G['setting']['site_style']:"red";
$theme_name = !empty($G['setting']['site_theme'])?$G['setting']['site_theme']:$theme_name;
$sections = array('site','message');
$smarty->configLoad('default.conf', $sections);
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$ADODB_CACHE_DIR = DATA_PATH.'dbcache';
$theme_img_name = !empty($style_name)?"style/".$style_name."/":'';
$smarty->setTemplateDir(PHPB2B_ROOT .$tpl_dir.DS."site".DS.$theme_name.DS, 'main');
$smarty->flash_layout = $theme_name."/flash";
$smarty->assign("theme_img_path", $tpl_dir. "/site/".$theme_name."/");
//$smarty->assign("theme_style_path", "templates/site/".$style_name."/");
$smarty->assign("theme_style_name", $style_name);
$smarty->assign("theme_img_name", $tpl_dir. "/site/".$theme_name."/".$theme_img_name);
$smarty->assign('theme_name', $theme_name);
$smarty->setCompileDir($smarty->getCompileDir()."site".DS.$theme_name.DS);
?>