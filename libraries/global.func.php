<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2218 $
 */
function da($arr_str, $exit = false)
{
	$x = "<pre>";
	$x .= print_r($arr_str, 1);
	$x .= "</pre>";
	print $x;
	($exit)?exit:'';
}

function pb_getenv($key) {
	if ($key == 'HTTPS') {
		if (isset($_SERVER['HTTPS'])) {
			return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
		}
		return (strpos(pb_getenv('SCRIPT_URI'), 'https://') === 0);
	}

	if ($key == 'SCRIPT_NAME') {
		if (pb_getenv('CGI_MODE') && isset($_ENV['SCRIPT_URL'])) {
			$key = 'SCRIPT_URL';
		}
	}

	$val = null;
	if (isset($_SERVER[$key])) {
		$val = $_SERVER[$key];
	} elseif (isset($_ENV[$key])) {
		$val = $_ENV[$key];
	} elseif (getenv($key) !== false) {
		$val = getenv($key);
	}

	if ($key === 'REMOTE_ADDR' && $val === pb_getenv('SERVER_ADDR')) {
		$addr = pb_getenv('HTTP_PC_REMOTE_ADDR');
		if ($addr !== null) {
			$val = $addr;
		}
	}

	if ($val !== null) {
		return $val;
	}

	switch ($key) {
		case 'SCRIPT_FILENAME':
			if (defined('SERVER_IIS') && SERVER_IIS === true) {
				return str_replace('\\\\', '\\', pb_getenv('PATH_TRANSLATED'));
			}
			break;
		case 'DOCUMENT_ROOT':
			$name = pb_getenv('SCRIPT_NAME');
			$filename = pb_getenv('SCRIPT_FILENAME');
			$offset = 0;
			if (!strpos($name, '.php')) {
				$offset = 4;
			}
			return substr($filename, 0, strlen($filename) - (strlen($name) + $offset));
			break;
		case 'PHP_SELF':
			return str_replace(pb_getenv('DOCUMENT_ROOT'), '', pb_getenv('SCRIPT_FILENAME'));
			break;
		case "REQUEST_URI":
			if(!isset($_SERVER['REQUEST_URI'])) {
			    $_SERVER['REQUEST_URI'] = substr($_SERVER['argv'][0], strpos($_SERVER['argv'][0], ';') + 1);
			}
			return $_SERVER['REQUEST_URI'];
			break;
		case 'CGI_MODE':
			return (PHP_SAPI === 'cgi');
			break;
		case 'HTTP_BASE':
			$host = pb_getenv('HTTP_HOST');
			if (substr_count($host, '.') !== 1) {
				return preg_replace('/^([^.])*/i', null, pb_getenv('HTTP_HOST'));
			}
			return '.' . $host;
			break;
		case 'HTTP_HOST':
			if(isset($_SERVER['SERVER_NAME'])){
				return $_SERVER['SERVER_NAME'];
			}else{
				return $_SERVER['HTTP_HOST'];
			}
			break;
	}
	return null;
}

function pb_strcomp($str1,$str2)
{
	if (strcmp(trim($str1),trim($str2)) == 0) {
		return true;
	}else {
		return false;
	}
}

function pb_radom($len=6,$recycle=1){
	$str = 'ABCDEFGHJKMNPQRSTUVWXYabcdefghjkmnpqrstuvwxy';
	$str.= '123456789';
	$str = str_repeat($str,$recycle);
	return substr(str_shuffle($str),0,$len);
}

function setvar($name,$var)
{
	global $smarty;
	$smarty->assign($name,$var);
}

function uaAssign($names)
{
	global $smarty;
	if (is_array($names)) {
		foreach ($names as $smt_key=>$smt_val) {
			$smarty->assign($smt_key,$smt_val);
		}
	}
}

function pheader($string, $replace = true, $http_response_code = 0) {
	$string = str_replace(array("\r", "\n"), array('', ''), $string);
	if(empty($http_response_code)) {
		@header($string, $replace);
	} else {
		@header($string, $replace, $http_response_code);
	}
	if(preg_match('/^\s*location:/is', $string)) {
		exit();
	}
}

function flash($message_title = '', $back_url = '', $pause = 3, $extra = '')
{
	global $smarty;
	if (empty($back_url)) {
		if (defined('CURSCRIPT')) {	
			$back_url = CURSCRIPT. ".php";
		}elseif (isset($_SERVER['HTTP_REFERER'])){
			$back_url = $_SERVER['HTTP_REFERER'];
		}else{
			$back_url = "javascript:;";
		}
	}
	$url = $back_url;
	$message_code = $message_title;
	$images = array("failed.png", "success.png", "notice.png");
	$styles = array("error", "true");
	if (empty($message_code) || !$message_code || $message_code=="failed") {
		$image = $images[0];
		$message = L('action_failed', "msg", $extra);
		$style = $styles[0];
	}elseif($message_code=="success" or true===$message_code or strstr("success", $message_code)){
		$image = $images[1];
		$style = $styles[1];
		$message = L("success", "msg", $extra);
	}else{
		$image = $images[2];
		$style = null;
		$message = L($message_code, "msg", $extra);
	}
	$smarty->assign('action_img', $image);
	$smarty->assign('action_style', $style);
	$smarty->assign('url', $url);
	$smarty->assign('message', $message);
	$smarty->assign('title', strip_tags($message));
	if($pause!=0){
		$smarty->assign('redirect', $smarty->redirect($url, $pause));
	}
	$smarty->assign('page_title', strip_tags($message));
	//add default flash page
	$smarty->addTemplateDir(PHPB2B_ROOT."templates".DS."errors".DS);
	//if (!$viewhelper->tpl_exists('flash'.$smarty->tpl_ext)) {
	//	die($message);
	//}
	if (defined("IN_OFFCE")) 
	$smarty->display("extends:layout".$smarty->tpl_ext."|".'flash'.$smarty->tpl_ext);
	else
	$smarty->display('flash'.$smarty->tpl_ext);
	exit();
}


function pb_create_folder($dir)
{
	return mkdir($dir, 0777, true);
	//@chmod($dir, 0777);
}

function pb_get_cache($models, $path = '')
{
	if (is_array($models)) {
		foreach ($models as $model) {
			$cache_file = $path?CACHE_PATH.$path."cache_".$model.".php":CACHE_PATH."cache_".$model.".php";
			if (file_exists($cache_file)) {
				include $cache_file;
			}
		}
	}else{
		$cache_file = $path?CACHE_PATH.$path."cache_".$models.".php":CACHE_PATH."cache_".$models.".php";
		if (file_exists($cache_file)) {
			include $cache_file;
		}
	}
}

//only for site front.
function render($filename = null, $exit = false)
{
	global $smarty, 
	$viewhelper, 
	$theme_name, 
	$cache_id, 
	$dir_name, 
	$default_html_filename, 
	$re_create_file;
	$return = false;
//	$tmp_themename = '';
//	$smarty->setTemplateDir(PHPB2B_ROOT ."templates".DS."site".DS, 'main');
	$smarty->assign('position', $viewhelper->getPosition());
	$smarty->assign('page_title', $viewhelper->getTitle());
	$tpl = $theme_name.DS.$filename.$smarty->tpl_ext;
	if ($theme_name=='blue' || !$viewhelper->tpl_exists($tpl)) {
//		$tmp_themename = 'default';
		$tpl = $filename.$smarty->tpl_ext;
	}
	if (empty($filename)) {
		//Todo:auto select template default
	}
//	$smarty->assign('ThemeName', $tmp_themename?$tmp_themename:$theme_name);
	if (!empty($viewhelper->metaDescription)) {
		$smarty->assign("metadescription", $viewhelper->metaDescription);		
	}
	if (!empty($viewhelper->metaKeyword)) {
		$smarty->assign("metakeywords", $viewhelper->metaKeyword);
	}elseif (!empty($viewhelper->metaDescription)){
		$viewhelper->setMetaKeyword($viewhelper->metaDescription);
		$smarty->assign("metakeywords", $viewhelper->metaKeyword);
	}
	if ($smarty->caching) {
		$cache_id = $_GET['page']."|".$_GET['id']."|".$_GET['pos'];
	}
	if (defined("SMARTY_CACHE") && SMARTY_CACHE){
		$smarty->caching = 1;
	}
	if ($smarty->caching) {
		$cache_id = substr(md5(pb_getenv('REQUEST_URI').$cache_id), 0, 16);
	}
	$return = $smarty->display($tpl, $cache_id);
	if ($exit) {
		exit;
	}
	return $return;
}

function template($filename = null, $exit = false)
{
	global $smarty;
	$return = false;
	$return = $smarty->display($filename.$smarty->tpl_ext);
	if ($exit) {
		exit;
	}
	return $return;
}

function pb_check_email($email){
	$return = false;
	if(strstr($email, '@') && strstr($email, '.')){
		if(preg_match("/^([_a-z0-9]+([\._a-z0-9-]+)*)@([a-z0-9]{2,}(\.[a-z0-9-]{2,})*\.[a-z]{2,4})$/", $email)){
			$return = true;
		}
	}
	return $return;
}

function usetcookie($var, $value, $life_time = 0, $prefix = 1) {
	global $cookiepre, $cookiepath, $time_stamp, $cookiedomain;
	return setcookie(($prefix ? $cookiepre : '').$var, $value,
	$life_time ? $time_stamp + $life_time : 0, $cookiepath,
	$cookiedomain, $_SERVER['SERVER_PORT'] == 443 ? 1 : 0);
}

function uclearcookies() {
	return usetcookie('auth', '', -86400 * 365);
}

function fileext($filename) {
	return substr(($t=strrchr($filename,'.'))!==false?".".$t:'',1);
}

function pb_htmlspecialchar($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = pb_htmlspecialchar($val);
		}
	} else {
		$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1',
		str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string));
	}
	return $string;
}

function pb_get_client_ip($type = "long")
{
	if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
		$onlineip = getenv('HTTP_CLIENT_IP');
	} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
		$onlineip = getenv('HTTP_X_FORWARDED_FOR');
	} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
		$onlineip = getenv('REMOTE_ADDR');
	} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$onlineip = $_SERVER['REMOTE_ADDR'];
	}
	preg_match("/[\d\.]{7,15}/", $onlineip, $onlineipmatches);
	$onlineip = $onlineipmatches[0] ? $onlineipmatches[0] : 'unknown';
	if($onlineip=='unknown') return $onlineip;
	if($type=="long"){
		return pb_ip2long($onlineip);
	}else{
		return $onlineip;
	}
}

function pb_ip2long($ip)
{
	return sprintf("%u",ip2long($ip));
}

function pb_addslashes($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = pb_addslashes($val);
		}
	} else {
		$string = addslashes($string);
	}
	return $string;
}

function stripslashes_recursive(&$array) {
	while(list($key,$var) = each($array)) {
		if ($key != 'argc' && $key != 'argv' && (strtoupper($key) != $key || ''.intval($key) == "$key")) {
			if (is_string($var)) {
				$array[$key] = stripslashes($var);
			}
			if (is_array($var))  {
				$array[$key] = stripslashes_recursive($var);
			}
		}
	}
	return $array;
}

function stripslashes_deep($value)
{
    if(isset($value)) {
        $value = is_array($value) ?
            array_map('stripslashes_deep', $value) :
            stripslashes($value);
    }
    return $value;
}

if (!function_exists('getmicrotime')) {
	function getmicrotime() {
		list($usec, $sec) = explode(' ', microtime());
		return ((float)$usec + (float)$sec);
	}
}

function pb_get_host($http = true)
{
	if ( isset( $_SERVER['HTTPS'] ) && ( strtolower( $_SERVER['HTTPS'] ) != 'off' ) ) {
		$ul_protocol = 'https';
	}else{
		$ul_protocol = 'http';
	}
	if($http) {
		$return = $ul_protocol."://".$_SERVER['HTTP_HOST'];
	} else {
		$return = $_SERVER['HTTP_HOST'];
	}
	return $return;
}

function uses() {
	$args = func_get_args();
	foreach($args as $arg) {
		$class_name = strtolower($arg);
		include(LIB_PATH . "core/controllers/".$class_name. '_controller.php');
		if(is_file($model_file = LIB_PATH . "core/models/".$class_name. '.php')) include($model_file);
	}
}

function using() {
	$args = func_get_args();
	foreach($args as $arg) {
		$class_name = strtolower($arg);
		require_once(LIB_PATH . "core/models/".$class_name. '.php');
	}
}

function pb_strip_spaces($string)
{
	$str = preg_replace('#\s+#', ' ', trim($string));
	return $str;
}

function pb_get_member_info()
{
	global $cookiepre;
	$user = array();
	if (!empty($_COOKIE[$cookiepre."auth"])) {
		list($user['pb_userid'], $user['pb_username'], $user['pb_userpasswd'], $user['is_admin']) = explode("\t", authcode($_COOKIE[$cookiepre."auth"], 'DECODE'));
	}else{
		list($user['pb_userid'], $user['pb_username'], $user['pb_userpasswd'], $user['is_admin']) = '';
	}
	return $user;
}

function authcode($string, $operation = "ENCODE", $key = '', $expire = 0) {
	global $phpb2b_auth_key;
	$ckey_length = 4;
	$key = md5($key ? $key : $phpb2b_auth_key);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expire ? $expire + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}
}

function L($key, $type = "", $extra = null){
	$return = $GLOBALS['smarty']->getConfigVars($key);
	if(empty($return)){
		$GLOBALS['smarty']->configLoad('default.conf', 'message');
		$return = $GLOBALS['smarty']->getConfigVars($key);
	}
	if (is_array($extra)) {
		$return = vsprintf($return, $extra);
	}else{
		$return = sprintf($return, $extra);
	}
	return (!empty($return))?pb_lang_split($return):$key;
}

function formhash() {
	global $time_stamp, $phpb2b_auth_key;
	return substr(md5(substr($time_stamp, 0, -4).$phpb2b_auth_key), 16);
}

function pb_submit_check($var) {
	$referer = pb_getenv('HTTP_REFERER');
//	if (is_file(DATA_PATH.'antispam'.DS.'index.php')) {
//		require(DATA_PATH.'antispam'.DS.'index.php');
//	}
	if(!empty($_POST[$var]) && $_SERVER['REQUEST_METHOD'] == 'POST') {
		if((empty($referer) || preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $referer) == preg_replace("/([^\:]+).*/", "\\1", pb_getenv('HTTP_HOST'))) && $_POST['formhash'] == formhash()) {
			return true;
		}
	}
	header_sent(L("invalid_submit"));
	exit;
}

function parse_highlight($highlight, $return_color = false) {
	if($highlight) {
		//as like to colorPicker, viewHelper
		$colorarray = array('#000000', '#FF0000', '#FFA500', '#FFFF00', '#008000', '#00FFFF', '#0000FF', '#800080', '#808080');
		$string = sprintf('%02d', $highlight);
		$stylestr = sprintf('%03b', $string[0]);
		if ($return_color) {
			$r['bold'] = $stylestr[0];
			$r['italic'] = $stylestr[1];
			$r['underline'] = $stylestr[2];
			$r['color'] = $colorarray[intval($string[1])];
			return $r;
		}
		$style = ' style="';
		$style .= $stylestr[0] ? 'font-weight: bold;' : '';
		$style .= $stylestr[1] ? 'font-style: italic;' : '';
		$style .= $stylestr[2] ? 'text-decoration: underline;' : '';
		$style .= $string[1] ? 'color: '.$colorarray[$string[1]] : '';
		$style .= '"';
	} else {
		$style = '';
	}
	return $style;
}

function pb_get_attachmenturl($src, $path = '', $size = '', $force = false)
{
	global $attachment_dir, $attachment_url;
	$default_thumb_img = STATICURL. 'images/nopicture_small.gif';
	if (empty($size)) {
		$size = "small";
	}
	switch ($size) {
		case "small":
			$scope = ".".$size;
			break;
		case "middle":
			$scope = ".".$size;
			break;
		case "country":
			return '<img src="'.STATICURL.'images/country/'.$src.'"/>';
			break;
		case "group":
			return '<img src="'.STATICURL.'images/group/'.$src.'"/>';
			break;
		default:
			$scope = "";
			break;
	}
	if (!empty($scope)) {
		$default_thumb_img = STATICURL. 'images/nopicture_'.$size.'.gif';
	}
	if ($force) {
		$default_thumb_img = STATICURL.'images/nopicture_'.$force.'.gif';
	}
	$img =  $src ? $attachment_url.$src : $default_thumb_img;
	if ($scope && ($img!=$default_thumb_img)) {
		$img.="{$scope}.jpg";
	}
	return $path.$img;
}


function capt_check($capt_name)
{
	global $_POST, $G, $smarty, $charset;
	$capt_require = array(
	"capt_logging",
	"capt_register",
	"capt_post_free",
	"capt_add_market",
	"capt_login_admin",
	"capt_apply_friendlink",
	"capt_service"
	);
	if (in_array($capt_name, $capt_require)) {
		$t = decbin($G['setting']['capt_auth']);
		$capt_auth = sprintf("%07d", $t);
		$key = array_search($capt_name, $capt_require);
		if($capt_auth[$key]){
			if (!empty($_POST['data'])) {
				include(LIB_PATH. "securimage/securimage.php");
				$img = new Securimage();
				$post_code = trim($_POST['data'][$capt_name]);
				header('Content-Type: text/html; charset='.$charset);
				if(!$img->check($post_code)){
					flash('invalid_capt', null, 0);
				}
			}
			$smarty->assign("ifcapt", true);
		}else{
			$smarty->assign("ifcapt", false);
		}
	}
}


function am() {
	$r = array();
	$args = func_get_args();
	foreach ($args as $a) {
		if (!is_array($a)) {
			$a = array($a);
		}
		$r = array_merge($r, $a);
	}
	return $r;
}

function header_sent($msg)
{
	global $charset;
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset='.$charset.'" />
</head>
<body>
<div>
'.$msg.'
</div>
</body>
</html>';
}

function check_proxy(){
	$v = getenv("HTTP_VIA");
	$f = getenv("HTTP_X_FORWARDED_FOR");
	$c = getenv("HTTP_XROXY_CONNECTION");
	$o = getenv("HTTP_PRAGMA");
	if ( ($v=="")&&($f=="")&&($c=="")&&($o=="") ) return false;
	return true;
}

function cache_read($file = null, $item = null, $prefix = true, $remove_params = null) {
	global $app_lang;
	$return = false;
	if($prefix) $file_name = CACHE_COMMON_PATH. 'cache_'.$file.'.php';
	else $file_name = CACHE_COMMON_PATH. $file.'.php';
//	$file_name = CACHE_ROOT. $app_lang. DS. 'cache_'.$file.'.php';
	if(!is_file($file_name)) return $return;
	$_required = require($file_name);
	if (empty($item)) {
		$item = $file;
	}
	if(isset($_PB_CACHE[$item])){
		$return = $_PB_CACHE[$item];
	}elseif(!empty($_PB_CACHE[$file])){
		$return = $_PB_CACHE[$file];
	}elseif(!empty($_PB_CACHE)){
		$return = $_PB_CACHE;
	}else{
		$return = $_required;
	}
	/**
	 * 暂时去除 2012.12.2
	 * 因为会引起 Fatal error: Allowed memory size of 134217728 bytes exhausted
	 */
	if (is_array($return)) {
		$return = array_map_recursive("pb_lang_split", $return);
	}
	if (!empty($remove_params)) {
		if (is_array($remove_params)) {
			foreach ($remove_params as $val) {
				unset($return[$val]);
			}
		}else{
			unset($return[$remove_params]);
		}
	}
	return $return;
}

function array_map_recursive($fn, $arr) {
    $ret = array();
    if (!empty($arr)) {
	    foreach ($arr as $key => $val) {
	        $ret[$key] = is_array($val)
	            ? array_map_recursive($fn, $val)
	            : $fn($val);
	    }
    }
    return $ret;
}

function df($timestamp = null, $format = null)
{
	global $time_stamp;
	$return = '';
	if (empty($timestamp)) {
		$timestamp = $time_stamp;
	}
	if (!empty($format)) {
		$return = date($format, $timestamp);
	}else{
		$return = date("Y-m-d", $timestamp);
	}
	return $return;
}

function sens_str($content, $to = "***")
{
	$str = $content;
	$badword = cache_read("words", "words");
	if(!empty($badword)){
		$badword1 = array_combine($badword,array_fill(0,count($badword),$to));
		$str = strtr($content, $badword1);	
	}
	return $str;
}

function pb_lang_enabled($lang_name, $languages) {
	return in_array($lang_name, $languages);
}

/**
 * merge multi language title to one column
 *
 * @param array $inputs multi language title
 * @return string one title
 */
function pb_lang_merge($inputs)
{
	global $G, $app_lang;
//	if(!empty($G['languages']))
	$_languages = array_keys(unserialize($G['setting']['languages']));
//	else
//	$_languages[] = $app_lang;
	$ret = '';
	$inputs = array_filter($inputs);
	foreach ($inputs as $key=>$val) {
		if(pb_lang_enabled($key, $_languages)) $ret.="[:".$key."]".$val;
	}
	return $ret;
}

function pb_lang_split_recursive($arr)
{
	$ret = array();
	if(!empty($arr))
	$ret = array_map_recursive("pb_lang_split", $arr);
	return $ret;
}

/**
 * split lang title
 *
 * @param string $text title
 * @param string $lang_name language name
 * @param boolean $all if return all
 * @return string or array
 */
function pb_lang_split($text, $all = false) {
	global $G, $app_lang;
	$lang_name = $app_lang;
	//convert to array	
	if(!empty($G['setting']['languages'])) 
		$_languages = array_keys(unserialize($G['setting']['languages']));
	else 
		$_languages[] = $lang_name;
	$ret = '';
	$split_regex = "#(<!--[^>]*[^\/]-->|\[:[-:a-z]{3,5}\])#ism";
	$current_language = "";
	$result = array();
	foreach($_languages as $language) {
		$result[$language] = "";
	}
	$blocks = preg_split($split_regex, $text, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
	foreach($blocks as $block) {
		if(preg_match("#^\[:([-:a-z]{3,5})\]$#ism", $block, $matches)) {
			if(pb_lang_enabled($matches[1], $_languages)) {
				$current_language = $matches[1];
			} else {
				$current_language = "invalid";
			}
			continue;
		}
		if($current_language == "") {
			foreach($_languages as $language) {
				$result[$language] .= $block;
			}
		} elseif($current_language != "invalid") {
			$result[$current_language] .= $block;
		}
	}
	if($all) return $result;
	else{
		$result = array_filter($result);
		if(isset($result[$lang_name])) $ret = $result[$lang_name];
		if(!empty($ret)) return $ret;
		elseif(current($result)) return current($result);//get the first
//		elseif(current($result)) return current($result)."[".key($result)."]";
		else return $text;//return orignal
	}
}

function clear_html($string)
{
	$farr = array(
	"/\s+/",
	"/<(\/?)(script|i?frame|style|html|body|title|link|meta|\?|\%)([^>]*?)>/isU",
	"/(<[^>]*)on[a-zA-Z]+\s*=([^>]*>)/isU",
	);
	$tarr = array(
	" ",
	"＜\1\2\3＞",
	"\1\2",
	);
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$str[$key] = clear_html($val);
		}
	} else {
		$str = preg_replace( $farr,$tarr,$string);
	}
	return $str;
}

function pb_hidestr($string)
{
	$ret = '';
	if(empty($string)){ 
		return false; 
	}
	$show = array('m_0', 'm_1', 'm_2', 'm_3', 'm_8', 'm_9', 'm_10', 'm_16', 'm_19', 'm_20', 'm_22', 'm_33', 'm_34', 'm_37', 'm_38', 'm_40', 'm_44', 'm_45', 'm_46', 'm_48');
	$hide = array('m_4', 'm_5', 'm_6', 'm_7', 'm_11', 'm_12', 'm_13', 'm_14', 'm_15', 'm_17', 'm_18', 'm_21', 'm_23', 'm_24', 'm_25', 'm_26', 'm_27', 'm_28', 'm_29', 'm_30', 'm_31', 'm_32', 'm_35', 'm_36', 'm_39', 'm_41', 'm_42', 'm_43', 'm_47', 'm_49');
	for($i=0;$i<strlen($string);$i++){
		$flag = mt_rand(0,1);
		if($flag){
			$show_style = array_rand($show);
			$ret .="<span class='".$show[$show_style]."'>".$string[$i]."</span>";
		}else{
			$hide_style = array_rand($hide);
			$ret .="<span class='".$hide[$hide_style]."'>".mt_rand(0,1000)."</span>";
			$i--;
		}
	}
	return $ret;
}

/**
 * format_tree
 *
 * @param unknown_type $datas
 * @param unknown_type $pid
 * @param unknown_type $sub_key maybe sub,children,child
 * @return unknown
 */
function pb_format_tree( $datas, $pid = null, $sub_key = 'sub' ) {
    $op = array();
    foreach( $datas as $item ) {
        if( $item['parent_id'] == $pid ) {
            $op[$item['id']] = array(
                'id' => $item['id'],
                'url' => $item['url'],
                'level' => $item['level'],
                'name' => $item['name'],
                'parent_id' => $item['parent_id']
            );
            // using recursion
            $op[$item['id']][$sub_key] = array();
            $children =  pb_format_tree( $datas, $item['id'] );
            if( $children ) {
                $op[$item['id']][$sub_key] = $children;
            }
        }
    }
    return $op;
}

function pb_configmake($lang, $exit = true)
{
	global $charset;
	//read the csv files at languages.
	$language_files = array();
	require_once SOURCE_PATH. 'Excel/reader.php';
	$reader = new Spreadsheet_Excel_Reader();
	foreach(glob(PHPB2B_ROOT.'languages/'.$lang.'/*.csv') as $single){
		$language_files[basename($single, ".csv")] = PHPB2B_ROOT.'languages/'.$lang.'/'.basename($single);
	}
	foreach(glob(PHPB2B_ROOT.'languages/'.$lang.'/*.xls') as $single){
		$language_files[basename($single, ".xls")] = PHPB2B_ROOT.'languages/'.$lang.'/'.basename($single);
	}
	if (!is_dir(PHPB2B_ROOT.'./languages/'.$lang)) {
		header_sent("Wrong with languages.");
		exit;
	}
	if (!is_dir(PHPB2B_ROOT.'data'.DS.'language'.DS.$lang)) {
		mkdir(PHPB2B_ROOT.'data'.DS.'language'.DS.$lang, 0777, true);
	}
	header("Content-type: text/html; charset=".$charset); 
	if (!empty($language_files)) {
		ksort($language_files);
		$read_me = file_get_contents(PHPB2B_ROOT.'./languages/'.$lang.'/readme.txt');
		if ($charset == "gbk") {
			//only for gbk chinese
			$read_me = iconv('gbk', $charset, $read_me);//if your language is utf-8,please delete this line.
		}
		$config_file = PHPB2B_ROOT.'data'.DS.'language'.DS.$lang.DS.'default.conf';
		//clear the file at config.
		file_put_contents($config_file, $read_me."\r\n\r\n\r\n");
		//global
		$file = $language_files['global'];
		$fp = fopen($file,'r');
		if (strtolower(fileext($file)) == ".xls") {
			$reader->setOutputEncoding('utf-8');
			$reader->read($file);
			for ($i = 1; $i <= $reader->sheets[0]['numRows']; $i++) {
				$title = trim($reader->sheets[0]['cells'][$i][1]);
				$content = trim($reader->sheets[0]['cells'][$i][2]);
				file_put_contents($config_file, $title." = \"".$content."\"\r\n", FILE_APPEND);
			}
		}else{
			while ($data = fgetcsv($fp, 1024, ",")) {
				$title = trim($data[0]);
				$content = $data[1];
				if ($charset == "gbk") {
					//only for gbk chinese
					$content = iconv('gbk', $charset, $content);//if your language is utf-8,please delete this line.
				}
				file_put_contents($config_file, $title." = \"".$content."\"\r\n", FILE_APPEND);
			}			
		}
		fclose($fp);
		//read csv contents
		unset($language_files['global']);
		foreach ($language_files as $key=>$file) {
			$fp = fopen($file,'r');
			file_put_contents($config_file, "\r\n[".$key."]\r\n", FILE_APPEND);
			if (strtolower(fileext($file)) == ".xls") {
				$reader->setOutputEncoding('utf-8');
				$reader->read($file);
				for ($i = 1; $i <= $reader->sheets[0]['numRows']; $i++) {
					$title = trim($reader->sheets[0]['cells'][$i][1]);
					$content = trim($reader->sheets[0]['cells'][$i][2]);
					file_put_contents($config_file, $title." = \"".$content."\"\r\n", FILE_APPEND);
				}
			}else{
				while ($data = fgetcsv($fp,1024, ",")) {
					$title = trim($data[0]);
					$content = $data[1];
					if ($charset == "gbk") {
						//only for gbk chinese
						$content = iconv('gbk', $charset, $content);//if your language is utf-8,please delete this line.
					}
					file_put_contents($config_file, $title." = \"".$content."\"\r\n", FILE_APPEND);
				}
			}
			fclose($fp);
		}
		if($exit){
			header_sent("Language package reloaded, you can <a href='javascript:location.reload()'>refresh</a> the page.");
			exit;
		}else{
			return ;
		}
	}else{
		header_sent("Wrong with language maken.");
		exit;
	}
}

function pb_attack_filter($StrFiltKey,$StrFiltValue,$ArrFiltReq){
	if(is_array($StrFiltValue))
	{
		$StrFiltValue=implode($StrFiltValue);
	}
	if (preg_match("/".$ArrFiltReq."/is",$StrFiltValue)==1){
		echo $ArrFiltReq;
		echo $StrFiltValue;
		header_sent("Warning : Illegal operation!");
		exit();
	}
}
function pb_hack_check(){
	$getfilter="'|(and|or)\\b.+?(>|<|=|in|like)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
	$postfilter="\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|ascii|load_file|substring|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
	$_PG=array_merge($_GET,$_POST);
	foreach($_PG as $key=>$value){
		pb_attack_filter($key,$value,$getfilter);
		pb_attack_filter($key,$value,$postfilter);
	}
}

function pb_ismobile() {
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
    {
        return true;
    } 
    if (isset ($_SERVER['HTTP_USER_AGENT']))
    {
        $clientkeywords = array ('nokia',
            'sony',
            'ericsson',
            'mot',
            'samsung',
            'htc',
            'sgh',
            'lg',
            'sharp',
            'sie-',
            'philips',
            'panasonic',
            'alcatel',
            'lenovo',
            'iphone',
            'ipod',
            'blackberry',
            'meizu',
            'android',
            'netfront',
            'symbian',
            'ucweb',
            'windowsce',
            'palm',
            'operamini',
            'operamobi',
            'openwave',
            'nexusone',
            'cldc',
            'midp',
            'wap',
            'mobile'
            ); 
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
        {
            return true;
        } 
    } 
    if (isset ($_SERVER['HTTP_ACCEPT']))
    { 
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
        {
            return true;
        } 
    } 
    return false;
}
?>