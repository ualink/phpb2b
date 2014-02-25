<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
class PbSessions {
	var $save_handler = "php";//memcache,mysql,apc
	var $security = "high";//high,medium,low
	var $lifetime = 1440;
	var $id;
	var $time;
	var $sesskey;
	var $expiry;
	var $last_activity;
	var $expireref;
	var $save_path;

    function __construct($save_path = '') {
    	global $G;
		$iniSet = function_exists('ini_set');
		$this->save_path = $save_path;
		if (empty($_SESSION)) {
			if ($iniSet && !empty($G['setting']['session_savepath'])) {
				if (isset($_SERVER['HTTPS'])) {
					ini_set('session.cookie_secure', 1);
				}
				//Todo:
				//ini_set('session.use_cookies', 1);
				//ini_set('session.cookie_lifetime', $this->lifetime);
				if(!empty($this->save_path)) {
					ini_set('session.save_path', $this->save_path);
				}elseif (defined("DATA_PATH")){
					session_save_path(DATA_PATH. "tmp".DS);
				}
			}
		}
		if (headers_sent()) {
			if (empty($_SESSION)) {
				$_SESSION = array();
			}
			return false;
		} elseif (!isset($_SESSION)) {
			session_cache_limiter ("must-revalidate");
			session_start();
			header ('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
			return true;
		} else {
			session_start();
			return true;
		}
    }
}
?>