<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
define('ANTI_FORCE', true);
define('ANTI_LEVEL', 3);//higher,will be more secure,default 3.
if(ANTI_FORCE){
	$GLOBALS['log']->lwrite($_SERVER['HTTP_USER_AGENT']);
	$ip_addr = pb_get_client_ip("long");
	if(strpos($referer,pb_getenv('HTTP_HOST'))===false || empty($ip_addr)){
		header_sent(L("invalid_submit"));
		exit;
	}
	$client_agent = $_SERVER['HTTP_USER_AGENT'];
	if (preg_match('/windows 2000/', $client_agent)){
		header("Location:".URL);
		exit;
	}
	$temp = explode('(', $client_agent);
	$Part = $temp[0];
	$ext_info = $temp[1];
	$ext_info = explode(')', $ext_info);
	$temp = explode(';', trim($ext_info[0]));
	$r_info = array();
	if (!empty($ext_info[1])) {
		$r_info = trim($ext_info[1]);
		$r_info = explode(" ", $r_info);
	}
	$temp = array_filter($temp);
	$browser_info = am($Part, $temp, $r_info);
	$ext_len = count($browser_info);
	if($ext_len<ANTI_LEVEL){
		header("Location:".URL);
		exit;
	}
}
?>