<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
class Passports extends PbObject
{
	public $names;
	public $config;
	public $passport_dir;
	public $passport_path;
	public $db;
	public $table_prefix;
	public $default_groupid = '';
	public $default_typeid = '';

	function __construct()
	{
		global $pdb, $tb_prefix,$charset;
		$this->db =& $pdb;
		$this->charset = $charset;
		$this->table_prefix = $tb_prefix;
		$this->passport_dir = 'passports';
		$this->passport_path = PHPB2B_ROOT. 'api'.DS;
	}
	
	function checkStatus($name = 'ucenter')
	{
		//check passports
		$sql = "SELECT id,title FROM {$this->table_prefix}passports WHERE available=1 AND name='".$name."'";
		$rs = $this->db->GetRow($sql);
		if (empty($rs) || !$rs) {
			return false;
		}
	}

	function install($entry)
	{
		$tpldir = realpath($this->passport_path.$entry);
		if (is_dir($tpldir)) {
			$this->params['data']['name'] = $entry;
			$this->params['data']['title'] = strtoupper($entry);
			$this->params['data']['available'] = 1;
			$this->params['data']['created'] = $this->params['data']['modified'] = $_SERVER['REQUEST_TIME'];
			$this->save($this->params['data']);
		}
	}

	function uninstall($id)
	{
		$sql = "DELETE FROM {$this->table_prefix}passports WHERE id=".$id;
		return $this->db->Execute($sql);
	}

	function getPassports(){
		$installed = $this->getInstalled();
		$not_installed = $this->getUninstalled();
		$all = array_merge($installed, $not_installed);
		return $all;
	}

	function getInstalled()
	{
		$sql = "SELECT * FROM {$this->table_prefix}passports WHERE available=1 or available=0";
		$result = $this->db->GetArray($sql);
		return $result;
	}

	function getUninstalled(){
		$uninstalled = $temp = array();
		$installed = $this->getInstalled();
		foreach($installed as $key=>$val){
			$temp[] = $val['name'];
		}
		$template_dir = dir($this->passport_path);
		while($entry = $template_dir->read()!== false)  {
			$tpldir = realpath($this->passport_path.DS.$entry);
			if((!in_array($entry, array('.', '..', '.svn'))) && (!in_array($entry, $temp)) && is_dir($tpldir)) {
				$uninstalled[] = array(
				'name' => $entry,
				'title' => strtoupper($entry),
				'available' => 0,
				);
			}
		}
		return $uninstalled;
	}

	function writeConfig($file_name, $config)
	{
		if(empty($config) || !is_array($config)) return false;
		$pattern = $replacement = array();
		foreach($config as $k=>$v)
		{
			$pattern[$k] = "/define\(\s*['\"]".strtoupper($k)."['\"]\s*,\s*([']?)[^']*([']?)\s*\)/is";
			$replacement[$k] = "define('".$k."', \${1}".$v."\${2})";
		}
		$str = file_get_contents($this->passport_path.$file_name);
		$str = preg_replace($pattern, $replacement, $str);
		return file_put_contents($this->passport_path.$file_name, $str);
	}
	
	function ucenter($username, $password, $email, $action = 'login')
	{
		$r = $this->checkStatus();
		if(!$r) return;
		include_once (API_PATH. 'ucenter/config.inc.php');
		include_once (API_PATH. "ucenter/uc_client/client.php");
		switch ($action) {
			case "login":
				$this->ucLogin($username, $password, $email);
				break;
			case "reg":
				$this->ucRegister($username, $password, $email);
				break;
			case "logout":
				$this->ucLogOut();
			default:
				break;
		}
	}

	function ucGetUserInfo($username){
		$r = $this->checkStatus();
		if(!$r) return;
		include_once (API_PATH. 'ucenter/config.inc.php');
		include_once (API_PATH. "ucenter/uc_client/client.php");
		$data = uc_get_user($username);
		if($data) {
			$default_groupid = DEFAULT_GROUP_ID;
			$default_typeid = DEFAULT_MEMBERTYPE_ID;
			if ($default_groupid>0) {
				$this->default_groupid = $default_groupid;
			}
			if ($default_typeid>0) {
				$this->default_typeid = $default_typeid;
			}
			list($uid, $username, $email) = $data;
			return array('uid'=>$uid, 'username'=>$username, 'email'=>$email);
		} else {
			return false;
		}
	}
	function ucSingleUpdatePwd($username, $newpwd, $oldpwd = '', $email = '', $ignoreoldpw = 1){
		$r = $this->checkStatus();
		if(!$r) return;
		include_once (API_PATH. 'ucenter/config.inc.php');
		include_once (API_PATH. "ucenter/uc_client/client.php");
		$ucresult = uc_user_edit($username, $oldpwd, $newpwd, $email, $ignoreoldpw);
		if($ucresult == 1) {
			return true;
		} else{
			return false;
		}
	}

	function ucSinleCheckPass($username, $password){
		$r = $this->checkStatus();
		if(!$r) return;
		include_once (API_PATH. 'ucenter/config.inc.php');
		include_once (API_PATH. "ucenter/uc_client/client.php");
		list($uid, $uname, $pass, $email) = uc_user_login($username, $password);
		if($uid > 0) {
			uc_user_synlogin($uid);
			return true;
		} else {
			return false;
		}
	}

	function ucLogin($username, $password, $useremail = null)
	{
		$r = $this->checkStatus();
		if(!$r) return;
		list($uid,$uname,$passwd,$email) = uc_user_login($username,$password);
		if($uid>0){
			@header('Content-Type: text/html; charset='.$this->charset);
			$jshead = uc_user_synlogin($uid);
			setvar("js_head", $jshead);
			flash(L('login_success'),'index.php');//must be this line,or will not syn to uc.
		}elseif($uid == -1){
			@header('Content-Type: text/html; charset='.$this->charset);
			//user not exist,will auto register
			$this->ucRegister($username, $password, $useremail);
		}elseif($this->checkStatus('ucenter') && $uid == -2) {
			@header('Content-Type: text/html; charset='.$this->charset);
			flash(L('login_wrong_passwd'), 'index.php');
		}
	}
	function ucLogOut(){
		include_once (API_PATH. 'ucenter/config.inc.php');
		include_once (API_PATH. "ucenter/uc_client/client.php");
		$jshead = uc_user_synlogout();
		setvar("js_head", $jshead);
		flash(L("logout_success"),'index.php');
	}
	function ucRegister($username, $password, $email){
		include_once (API_PATH. 'ucenter/config.inc.php');
		$uid = uc_user_register($username, $password, $email);
		if($uid <= 0) {
			header('Content-Type: text/html; charset='.$this->charset);
			if($uid == -1) {
				die(L('pp_elegal_username'));
			} elseif($uid == -2) {
				die(L('pp_not_allowed_words'));
			} elseif($uid == -3) {
				die(L('pp_username_exist'));
			}elseif($uid == -5) {
				die(L('pp_email_not_allowed'));
			} elseif($uid == -6) {
				die(L('pp_email_registerd'));
			}
		}
	}
}
?>