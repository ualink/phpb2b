<?php
class Adminfields extends PbModel {
 	var $name = "Adminfield";
 	var $userid;
 	var $username;
 	var $userpass;
 	var $error;
 	var $checkip = 1;
 	var $info;

 	function __construct()
 	{
		parent::__construct();
 	}

	function checkUserLogin($uname, $upass, $set = true)
	{
		$uname = trim($uname);
		$upass = trim($upass);
		$_this = Members::getInstance();
		if (empty($uname) || empty($upass)){
			return -1;
		}
		$sql = "SELECT m.id,m.username,m.userpass,af.first_name,af.last_name,af.expired FROM {$this->table_prefix}adminfields af LEFT JOIN {$this->table_prefix}members m ON af.member_id=m.id WHERE m.username='$uname'";
		$tmpUser = $this->dbstuff->GetRow($sql);
		if(!$_this->checkUserExist($uname)) {
			$this->error = L("member_not_exists");
			return -2;
		}elseif($tmpUser['expired']!=0 && $tmpUser['expired']<$this->timestamp){
			$this->error = L("account_expired");
			return;
		}elseif (!pb_strcomp($tmpUser['userpass'],$_this->authPasswd($upass))){
			$this->error = L("login_pwd_wrong");
			return -3;
		}else {
			$this->dbstuff->Execute("UPDATE {$this->table_prefix}adminfields SET last_login=".$this->timestamp.",last_ip='".pb_get_client_ip("str")."' WHERE member_id=".$tmpUser['id']);
    		$tAuth = $tmpUser['id']."\n".$tmpUser['username']."\n".$tmpUser['userpass'];
    		usetcookie("admin", authcode($tAuth, "ENCODE"));
			return true;
		}
	}
	
	function updatePasswd($user_id, $user_pass)
	{
		$_this = Members::getInstance();
		$result = false;
		$sql = "UPDATE {$this->table_prefix}members m,{$this->table_prefix}adminfields af SET m.userpass='".$_this->authPasswd($user_pass)."',af.modified=".$this->timestamp." WHERE m.id=".$user_id." AND af.member_id=m.id";
		$result = $this->dbstuff->Execute($sql);
		return $result;
	}
	
	function loadsession($user_id, $ip, $checkip = 1)
	{
		$session = array();
		$sql = "select m.userpass,af.last_login,af.last_ip,af.first_name,af.last_name,af.member_id,af.permissions from {$this->table_prefix}members m,{$this->table_prefix}adminfields af WHERE m.id='".$user_id."' ".($checkip ? "AND af.last_ip='$ip'" : '')." AND m.id=af.member_id";
		$result = $this->dbstuff->GetRow($sql);
		$this->info = $result;
		if (empty($result) || !$result) {
			echo "<script language='javascript'>top.location.href='login.php';</script>";
			exit;
		}elseif ($checkip && !pb_strcomp($this->info['last_ip'], $ip)) {
			echo "<script language='javascript'>top.location.href='login.php';</script>";
			exit;
	    }
	}
}
?>