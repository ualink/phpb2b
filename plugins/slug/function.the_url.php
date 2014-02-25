<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2244 $
 */
function smarty_function_the_url($params){
	global $subdomain_support, $topleveldomain_support, $rewrite_able, $rewrite_compatible, $absolute_uri;
	$url = "##";
	$html = "html";
	$file = 'index.php';
	extract($params);
	if (isset($action)) {
		if ($action == 'list' or $action == 'search') {
			$params['action'] = 'lists';
		}
	}elseif(!empty($id)){
		$params['action'] = 'detail';
	}
	if (isset($params['module']) && $module == "userpage") {
		$params['do'] = "page";
	}
	if (!empty($do)) {
		if ($do == "search") {
			if (empty($action)) {
				$params['action'] = 'lists';
			}
		}
		if ($do=="userpage") {
			$params['do'] = "page";
		}
		if ($do == "company") {
			if (!empty($userid)) {
				$params['do'] = "space";
				$params['userid'] = $userid;
				unset($params['action']);
			}
		}
	}elseif (!empty($module)){
		unset($params['module']);
		$params['do'] = $module;
	}
	$route_params = array_filter($params);
	if ($rewrite_able && $route_params['action'] == "detail") {
		$id = $route_params['id'];
		$action = $route_params['action'];
		unset($route_params['action'], $route_params['id'], $route_params['typeid']);
		$route_params['action'] = $action;
		$route_params['id'] = $id;
		if (!empty ($route_params) && is_array($route_params)) $url = implode('/', $route_params);
		if (!empty ($html)) $url .= '.' . $html;
	} else {
		$url = ($file == 'index') ? '' : '' . $file;
		if (substr($url, -4) != '.php' && $file != 'index') $url .= '.php';
		if (!empty ($route_params) && is_array($route_params)) $url .= '?' . http_build_query($route_params);
	}
	return $absolute_uri.$url;
}
?>