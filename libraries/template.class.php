<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2161 $
 */
require(LIB_PATH . "smarty/Smarty.class.php");
class TemplateEngines extends Smarty {
	var $flash_layout = 'flash';
	var $tpl_ext = '.html';
	var $compile_sub_dirs = true;

	function __construct()
	{
		global $debug, $app_lang;
		parent::__construct();
		if (isset($debug)) {
			switch ($debug) {
				case 1:
					error_reporting(E_ALL & ~E_DEPRECATED);
					if(function_exists('ini_set')) {
						ini_set('display_errors', 1);
					}
					break;
				case 2:
					error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
					if(function_exists('ini_set')) {
						ini_set('display_errors', 1);
					}
					break;
				case 3:
					error_reporting(E_ERROR);
					if(function_exists('ini_set')) {
						ini_set('display_errors', 1);
					}
					$GLOBALS['pdb']->debug = true;
					break;
				case 4:
					error_reporting(E_ALL);
					if(function_exists('ini_set')) {
						ini_set('display_errors', 1);
					}
					$GLOBALS['pdb']->debug = true;
					break;
				case 5:
					error_reporting(E_ALL);
					if(function_exists('ini_set')) {
						ini_set('display_errors', 1);
					}
					$GLOBALS['pdb']->debug = true;
					$this->debugging   = true;
					break;
				default:
					error_reporting(0);
					if(function_exists('ini_set')) {
						ini_set('display_errors', 0);
					}
					break;
			}
		}
		$this->smarty->addPluginsDir(SLUGIN_PATH)
			->addTemplateDir(PHPB2B_ROOT ."templates".DS, 'main')
			->setConfigDir(array('config'=>PHPB2B_ROOT ."configs".DS, 'lang'=>PHPB2B_ROOT."data".DS."language".DS.$app_lang.DS))
			->setCompileDir(DATA_PATH."templates_c".DS.$app_lang.DS)
			->setCacheDir(DATA_PATH."templates_cache".DS.$app_lang.DS);
//		$this->setCacheLifetime(0);
//		$this->setCompileCheck(true);
		$this->setCaching(Smarty::CACHING_OFF);
//		$this->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
		$this->loadFilter('pre','fix');
	}
	
	function flash($message_code, $url, $pause = 1, $extra = '') {
		exit($message_code);
	}
	
	function redirect($url, $pause) {
	
		return "<script>\n".
		"function redirect() {\nwindow.location.replace('$url');\n}\n".
		"setTimeout('redirect();', ".($pause*1000).");\n".
		"</script>";
	}
}
?>