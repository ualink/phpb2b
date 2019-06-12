<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2048 $
 */
if(!defined('APP_PATH')) define('APP_PATH', PHPB2B_ROOT .'app'.DS);
if(!defined('PLUGIN_PATH')) define('PLUGIN_PATH', PHPB2B_ROOT .'plugins'.DS);
if(!defined('SLUGIN_PATH')) define('SLUGIN_PATH', PHPB2B_ROOT .'libraries'.DS.'slug'.DS);
//Cache sets
if(!defined('DATA_PATH')) define('DATA_PATH', PHPB2B_ROOT."data".DS);
if(!defined('CACHE_ROOT')) define('CACHE_ROOT', PHPB2B_ROOT."data".DS."cache".DS);
if(!defined('API_PATH')) define('API_PATH', PHPB2B_ROOT."api".DS);
if (!defined('PHP5')) {
	define('PHP5', (PHP_VERSION >= 5));
}
if(!defined('SOURCE_PATH')) define('SOURCE_PATH',PHPB2B_ROOT.'libraries'.DS);
if(!defined('CLASS_PATH')) define('CLASS_PATH',PHPB2B_ROOT.'libraries'.DS.'source'.DS);
if(!defined('LIB_PATH')) define('LIB_PATH',PHPB2B_ROOT.'libraries'.DS);
if(!defined('STATICURL')) define('STATICURL', !empty($staticurl) ? $staticurl : 'static/');
if(!defined('JSMIN_AS_LIB')) define('JSMIN_AS_LIB', true); // prevents auto-run on include
?>