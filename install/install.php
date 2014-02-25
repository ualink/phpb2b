<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2048 $
 */
session_start();
error_reporting(E_ERROR | E_NOTICE);
set_magic_quotes_runtime(0);
ini_set('magic_quotes_sybase', 0);
ini_set('max_execution_time', '300');
if (isset($_GET['act'])) {
	if($_GET['act'] == "phpinfo"){
		die(phpinfo());
	}
}
if (!defined('DIRECTORY_SEPARATOR')) {
	define('DIRECTORY_SEPARATOR','/');
}
define('DS', DIRECTORY_SEPARATOR);
define('TIME', time());
define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
define('PHPB2B_ROOT', substr(dirname(__FILE__), 0, -7));
define('MIN_PHP_VERSION', '5.0.0');
//define('JSMIN_AS_LIB', true); // prevents auto-run on include
require '../phpb2b_version.php';
require '../configs/config.inc.php';
require '../libraries/core/paths.php';
if (version_compare(PHP_VERSION, MIN_PHP_VERSION, '<')) {
    echo 'PHPB2B '. PHPB2B_VERSION. ' require php'. MIN_PHP_VERSION.', but your php version is ' . PHP_VERSION . ".\n";
    exit;
}
define('IN_PHPB2B',true);
if(!defined('LIB_PATH')) define('LIB_PATH',PHPB2B_ROOT.'libraries'.DS);
require '../libraries/global.func.php';
require '../libraries/func.sql.php';
require "../libraries/db_mysql.inc.php";
require "../libraries/core/object.php";
require "../libraries/core/controller.php";
require "../libraries/file.class.php";
require "../libraries/template.class.php";
require "../libraries/source/error.class.php";
list($accept_language) = explode(",", $_SERVER['HTTP_ACCEPT_LANGUAGE']);
if(file_exists(PHPB2B_ROOT. 'languages'.DS.strtolower($accept_language).DS."global.csv")) {
	$app_lang = strtolower($accept_language);
}
if (!is_writable(PHPB2B_ROOT."data".DS)) {
	echo PHPB2B_ROOT."data".DS. " is not writeable.\n";
    exit;
}
if (!empty($_GET['app_lang'])) {
	$app_lang = $_GET['app_lang'];
}
if (!is_file(PHPB2B_ROOT."data".DS."language".DS.$app_lang.DS."default.conf")) {
	pb_configmake($app_lang);
}
if (!defined('CACHE_PATH')) {
	define('CACHE_PATH', PHPB2B_ROOT."data".DS."cache".DS.$app_lang.DS);
	define('CACHE_LANG_PATH', PHPB2B_ROOT."data".DS."cache".DS.$app_lang.DS);
	define('CACHE_COMMON_PATH', PHPB2B_ROOT."data".DS."cache".DS);
}
//language
$smarty = new TemplateEngines();
$sections = array('install', 'javascript');
//da($smarty);
$smarty->configLoad('default.conf', $sections);
$arrTemplate = $smarty->getConfigVars();
extract($arrTemplate);
//:~
$db = new DB_Sql();
$file_cls = new Files();
$pb_protocol = 'http';
if ( isset( $_SERVER['HTTPS'] ) && ( strtolower( $_SERVER['HTTPS'] ) != 'off' ) ) {
	$pb_protocol = 'https';
}
$PHP_SELF = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : preg_replace("/(.*)\.php(.*)/i", "\\1.php", $_SERVER['PHP_SELF']);
$BASESCRIPT = basename($PHP_SELF);
list($BASEFILENAME) = explode('.', $BASESCRIPT);
$install_url = htmlspecialchars($pb_protocol."://".pb_getenv('HTTP_HOST').preg_replace("/\/+(api|wap)?\/*$/i", '', substr($PHP_SELF, 0, strrpos($PHP_SELF, '/'))).'/');
$siteUrl = substr($install_url,0,-(strlen($BASEFILENAME)+1));
$time_stamp = TIME;
if($_REQUEST)
{
	if(!MAGIC_QUOTES_GPC)
	{
		$_REQUEST = pb_addslashes($_REQUEST);
		if($_COOKIE) $_COOKIE = pb_addslashes($_COOKIE);
	}
	extract($_REQUEST, EXTR_SKIP);
}
if(!isset($_GET['step'])) {
	$step = '1';
}else{
	$step = intval($_GET['step']);
}
if (isset($_GET['do'])) {
	$do = trim($_GET['do']);
	if($do == "complete"){
		include "step".$step.".inc.php";
		exit;
	}
}
if(file_exists(PHPB2B_ROOT.'data/install.lock')) {
	$msg = L("install_locked", "tpl");
	Errors::showError($msg);
	exit;
}
$license_file_name = "LICENSE.txt";
if (!file_exists(PHPB2B_ROOT.$license_file_name)) {
	$msg = L("license_not_exists");
	Errors::showError($msg);
	exit;
}
$backupdir = pb_radom(6);
$db_error = false;
switch($step)
{
	case '1':
	include "step".$step.".inc.php";

	break;
	case '2':
	$license = file_get_contents(PHPB2B_ROOT.$license_file_name);
	include "step".$step.".inc.php";
	break;

	case '3':
	$gd_support = '';
	if(extension_loaded('gd'))
	{
		if(function_exists('imagepng')) $gd_support .= 'png';
		if(function_exists('imagejpeg')) $gd_support .= ' jpg';
		if(function_exists('imagegif')) $gd_support .= ' gif';
	}
	$is_right = (phpversion() >= '4.3.0' && extension_loaded('mysql')) ? 1 : 0;
	include "step".$step.".inc.php";
	break;
	case '4':
	$files = file("chmod.txt");
	$files = array_filter($files);
	$writablefile = $no_writablefile = null;
	foreach($files as $file)
	{
		$file = str_replace('*','',$file);
		$file = trim($file);
		if(!is_writable('../'.$file)){
			$no_writablefile .= $file.' '."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&times;<br>";
		}else{
			$writablefile .= $file.' '.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&radic;<br>';
		}
	}

	include "step".$step.".inc.php";
	break;

	case '5':

	include "step".$step.".inc.php";
	break;

	case '6':
	$dbhost = $_POST['dbhost'];
	$dbuser = $_POST['dbuser'];
	$dbpasswd = $_POST['dbpw'];
	$dbname = $_POST['dbname'];
	$tablepre = $_POST['tablepre'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	$createdb = $_POST['db']['create'];
	$email = $_POST['email'];
	$passwordkey = $_POST['password_key'];
	$conn = mysql_connect($dbhost, $dbuser, $dbpasswd);
	if(!$conn){
		$error_info = mysql_errno()." : ".mysql_error();
		$db_error = true;
	}
	include "step".$step.".inc.php";
	break;

	case '7':
	$sitename = $_POST['sitename'];
	if(isset($_POST['testdata'])){
		$testdata = $_POST['testdata'];
	}
	$configs['dbhost'] = $dbhost = $_POST['dbhost'];
	$configs['dbuser'] = $dbuser = $_POST['dbuser'];
	$configs['dbpasswd'] = $dbpasswd = $_POST['dbpw'];
	$configs['dbname'] = $dbname = $_POST['dbname'];
	$configs['tb_prefix'] = $tb_prefix = $_POST['tablepre'];
	$configs['dbcharset'] = $dbcharset;
	$configs['pconnect'] = $pconnect;
	$username = $_POST['username'];
	$password = $_POST['password'];
	$createdb = $_POST['createdb'];
	$configs['admin_email'] = $email = $_POST['email'];
	$passwordkey = $_POST['password_key'];
	if (empty($passwordkey)) {
		$passwordkey = pb_radom(16);
	}
	$configs['absolute_uri'] = $siteurl = $_POST['siteurl'];
	if(empty($passwordkey)){
		$passwordkey = pb_radom(16);
	}
	if(empty($sitename)){
		$sitename = L("a_new_b2b_site", "tpl");
	}
	if (empty($sitetitle)) {
		$sitetitle = L("a_new_b2b_title", "tpl");
	}
	$conn = mysql_connect($dbhost, $dbuser, $dbpasswd);
	if($conn){
		$version = mysql_get_server_info();
		$set_names = "SET NAMES '$dbcharset'";
		$set_modes = "SET sql_mode=''";
		config_edit($configs);
		if($version > '4.1' && $charset)
		{
			mysql_query($set_names, $conn);
		}
		if($version > '5.0')
		{
			mysql_query($set_modes, $conn);
		}
		if(!mysql_select_db($dbname))
		{
			if ($createdb==1) {
				if(mysql_get_server_info() > '4.1') {
					mysql_query("CREATE DATABASE IF NOT EXISTS"
					." $dbname DEFAULT CHARACTER SET $dbcharset;");
				} else {
					mysql_query("CREATE DATABASE IF NOT EXISTS $dbname;");
				}
				mysql_close();
			}else{
				$error_info = mysql_errno()." : ".mysql_error()."<br>";
				$db_error = true;
				break;
			}
		} else {
			$sqldump = null;
			$conn = $db->connect($dbname,$dbhost,$dbuser,$dbpasswd);
			if($version > '4.1' && $charset)
			{
				$db->query($set_names);
			}
			if($version > '5.0')
			{
				$db->query($set_modes);
			}
			$tables = $db->table_names();
			if(!empty($tables)){
				foreach ($tables as $names) {
					if(!function_exists("stripos")){
                          function stripos($str,$needle) {
                                return strpos(strtolower($str),strtolower($needle));
                                     }
                           }
						if(stripos($names['table_name'],$tb_prefix) ===0){
							$sqldump.=data2sql($names['table_name']);
						}
					}
				
				pb_create_folder(PHPB2B_ROOT. DS. "data".DS."backup_".$backupdir);
				$file_path = PHPB2B_ROOT. DS. "data".DS."backup_".$backupdir.DS.date('ymd').'_'.pb_radom().".sql";
				if(trim($sqldump)) {
					file_put_contents($file_path ,$sqldump);
					unset($sqldump);
				}
			}
			$db->free();
		}
		ob_start();
		$schema_path = "data/schemas/".$app_lang."/";
		$schema_common_path = "data/schemas/";
		if (!file_exists($schema_path)) {
			die(L("congratulate", "msg", $schema_path));
		}
		if(file_exists($schema_common_path. "mysql.sql"))
		{
			$conn = $db->connect($dbname,$dbhost,$dbuser,$dbpasswd);
			if($version > '4.1' && $charset)
			{
				$db->query($set_names);
			}
			if($version > '5.0')
			{
				$db->query($set_modes);
			}
			$sqls = file_get_contents($schema_common_path. "mysql.sql");
			$r = sql_run($sqls);
			if (!$r) {
				Errors::showError(mysql_error(), 'db');
				exit;
			}
			$must_sql_data = file_get_contents($schema_path. "mysql.data.sql");
			$r = sql_run($must_sql_data);
			if (!$r) {
				Errors::showError(mysql_error(), 'db');
				exit;
			}
			$structure_sql_data1 = file_get_contents($schema_common_path. "mysql.data.area.sql");
			$r = sql_run($structure_sql_data1);
			$structure_sql_data2 = file_get_contents($schema_common_path. "mysql.data.industry.sql");
			$r = sql_run($structure_sql_data2);
			@touch(PHPB2B_ROOT.'./data/install.lock');
			if(!empty($testdata)){
				$source = "data/attachment/sample";
				$dest ="../attachment/sample";
				$sqls = file_get_contents($schema_path. "mysql.sample.sql");
				sql_run($sqls);
				dir_copy($source,$dest,1);
			}else{
				//basic datas
				$source = "data/attachment/sample/banner";
				$dest ="../attachment/sample/banner";
				dir_copy($source,$dest);
			}
			//language:~
			$show_languages = showLanguages(true);
			$languages = serialize($show_languages);
			$db->query(sprintf("REPLACE INTO {$tb_prefix}settings (variable, valued) VALUES ('languages', '%s')", $languages));
			//:~
			$db->query("REPLACE INTO {$tb_prefix}settings (variable, valued) VALUES ('install_dateline', '".$time_stamp."')");
			$db->query("REPLACE INTO {$tb_prefix}settings (variable, valued) VALUES ('site_name', '$sitename')");
			$db->query("REPLACE INTO {$tb_prefix}settings (variable, valued) VALUES ('site_title', '".htmlspecialchars($sitetitle)." - Powered By ".$software_name."')");
	
			$db->query("REPLACE INTO {$tb_prefix}settings (variable, valued) VALUES ('backup_dir', '".$backupdir."')");
			$db->query("REPLACE INTO {$tb_prefix}settings (variable, valued) VALUES ('site_url', '".$siteurl."')");
			$db->query("REPLACE INTO {$tb_prefix}settings (variable, valued) VALUES ('watertext', '".$siteurl."')");
			$db->query("REPLACE INTO {$tb_prefix}settings (variable, valued) VALUES ('auth_key', '$passwordkey')");
			$aminer_id = 1;
			$db->query("REPLACE INTO {$tb_prefix}members (id,username, userpass,email,membertype_id,membergroup_id,created,modified,status) VALUES ({$aminer_id},'{$username}','".md5($password)."','{$email}',2,9,".$time_stamp.",".$time_stamp.",'1')");
			$db->query("REPLACE INTO {$tb_prefix}adminfields (member_id,last_name,created,modified) VALUES ('{$aminer_id}','".L("administrator", "tpl")."',".$time_stamp.",".$time_stamp.")");	
			$db->free();
			require(PHPB2B_ROOT. "libraries".DS.'adodb'.DS.'adodb.inc.php');
			require(PHPB2B_ROOT. "libraries".DS."cache.class.php");
			$pdb = &NewADOConnection($database);
			$cache = new Caches();
			$conn = $pdb->PConnect($dbhost,$dbuser,$dbpasswd,$dbname);
			if($dbcharset && mysql_get_server_info() > '4.1') {
				$pdb->Execute("SET NAMES '{$dbcharset}'");
			}
			$cache->writeCache("setting", "setting");
			$cache->writeCache("industry", "industry");
			$cache->writeCache("area", "area");
			$cache->writeCache("membergroup", "membergroup");
			$cache->writeCache("userpage", "userpage");
			$cache->writeCache("trusttype", "trusttype");
			$cache->writeCache("form", "form");
			$cache->writeCache("nav", "nav");
			$cache->writeCache("country", "country");
			$cache->updateTypevars();
//			$cache->updateLanguages();
			$cache->writeCache("javascript", "javascript");
			$cache->updateTypes();
			$cache->updateIndexCache();
			header("Location:install.php?step={$step}&do=complete&app_lang=".$app_lang);
		}
		else
		{
			$db_error = true;
			break;
		}
	}else{
		$db_error = true;
		break;
	}
	break;
}
function config_edit($configs) {
	global $dbcharset, $app_lang;
	if (!is_array($configs)) {
		return;
	}
	extract($configs);
	$configfile = PHPB2B_ROOT. 'configs'.DS.'config.inc.php';
	$configfiles = file_get_contents($configfile);
	$configfiles = trim($configfiles);
	$configfiles = preg_replace("/[$]dbhost\s*\=\s*[\"'].*?[\"'];/is", "\$dbhost = '$dbhost';", $configfiles);
	$configfiles = preg_replace("/[$]app_lang\s*\=\s*[\"'].*?[\"'];/is", "\$app_lang = '$app_lang';", $configfiles);
	$configfiles = preg_replace("/[$]dbuser\s*\=\s*[\"'].*?[\"'];/is", "\$dbuser = '$dbuser';", $configfiles);
	$configfiles = preg_replace("/[$]dbpasswd\s*\=\s*[\"'].*?[\"'];/is", "\$dbpasswd = '$dbpasswd';", $configfiles);
	$configfiles = preg_replace("/[$]dbname\s*\=\s*[\"'].*?[\"'];/is", "\$dbname = '$dbname';", $configfiles);
	$configfiles = preg_replace("/[$]admin_email\s*\=\s*[\"'].*?[\"'];/is", "\$admin_email = '$admin_email';", $configfiles);
	$configfiles = preg_replace("/[$]tb_prefix\s*\=\s*[\"'].*?[\"'];/is", "\$tb_prefix = '$tb_prefix';", $configfiles);
	$configfiles = preg_replace("/[$]cookiepre\s*\=\s*[\"'].*?[\"'];/is", "\$cookiepre = '".pb_radom(3)."_';", $configfiles);
	$configfiles = preg_replace("/[$]absolute_uri\s*\=\s*[\"'].*?[\"'];/is", "\$absolute_uri = '".$absolute_uri."';", $configfiles);
	if(file_put_contents($configfile, $configfiles)){
		return true;
	}else{
		return false;
	}
}
function dir_copy($source, $destination, $child){
     if(!is_dir($destination)){  
     	mkdir($destination,0777,true);  
     }  
     $handle=dir($source);  
     while($entry=$handle->read()) {  
         if(!in_array($entry, array('.', '..', '.svn'))){  
             if(is_dir($source."/".$entry)){  
                 if($child)    {
                 	dir_copy($source."/".$entry,$destination."/".$entry,$child);  
                 }
             }else{  
                 copy($source."/".$entry,$destination."/".$entry);  
             }  
         }  
     }
     return true;  
}
function showLanguages($return_arr = false)
{
	global $app_lang, $charset;
	$return = $datas = array();
	$path = '../languages/';
	$handle = opendir($path);
	$setting_controller = new PbController();
	while(false !== $file=(readdir($handle))){
		$dir = $path.$file;
		if(is_dir($dir) && !in_array($file, array('.', '..', '.svn'))){
			$tmp = "<option value='".$file."'";
			if($app_lang==$file) {
				$tmp.=" selected='selected'";
			}elseif (isset($_GET['app_lang']) && $_GET['app_lang'] == $file){
				$tmp.=" selected='selected'";
			}
			$templet_file = PHPB2B_ROOT."languages/".$file."/readme.txt";
			$data = $setting_controller->getSkinData($templet_file);
			$title = $data['Name'];
			if ($charset != "utf-8") {
				//only for gbk chinese convert
				$title = iconv('gbk', $charset, $title);
			}
			$tmp.=">".$title."</option>";
			$return[] = $tmp;
			$datas[$file]['title'] = $title;
			if(is_file(PHPB2B_ROOT."languages/".$file."/icon.gif"))
			$datas[$file]['img'] = "languages/".$file."/icon.gif";
		}
	}
	if($return_arr){
		return $datas;
	}elseif (!empty($return)) {
		return implode("\r\n", $return);
	}else{
		return false;
	}
	closedir($handle);
}
function dequote($string)
{
	if ((substr($string, 0, 1) == "'" || substr($string, 0, 1) == '"') &&
	substr($string, -1) == substr($string, 0, 1))
	return substr($string, 1, -1);
	else
	return $string;
}
?>