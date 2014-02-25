<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
define('CURSCRIPT', 'index');
//$cache_archiver = true;
$htmlize = true;
require("libraries/common.inc.php");
require("share.inc.php");
if (!empty($_GET['do'])) {
	$action = isset($_GET['action']) ?trim($_GET['action']) : 'index' ;
	$do = $_GET['do'] == '' ? 'home' : htmlspecialchars(trim($_GET['do']));
	$c_file = LIB_PATH .'core/controllers/'.$do.'_controller.php';
	$c_do = ucwords($do);
	$secure_c = array(
	"rss",
	"offer",
	"news",
	"job",
	"fair",
	"space",
	"pc",
	"wap",
	"pad",
	"help",
	"friendlink",
	"page",
	"standard",
	"brand",
	"company",
	"announce",
	"error",
	"product",
	"market",
	"member",
	"message",
	"topic",
	"service",
	"search",
	"dict"
	);
	if(in_array($do, $secure_c)){
		if(is_file($c_file)){
			require($c_file);
			$pc = new $c_do();
			if($action == 'list') $action = 'lists';
			if(method_exists($pc, $action)) 
				call_user_func_array(array($pc, $action), array());
			elseif (method_exists($pc, 'index'))
				$pc->index();
		}else{
			render("index");
		}
	}else{
		flash();
	}
}elseif(pb_ismobile()){
	pheader("location:index.php?do=wap");
}else{
	render("index");
}
?>