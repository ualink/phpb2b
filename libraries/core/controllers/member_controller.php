<?php
class Member extends PbController {
	var $name = "Member";
	var $info;
	var $id;
	
	function __construct()
	{
		$this->loadModel("member");
	}
	
	function reactive()
	{
		global $G;
		if (!empty($_GET['em'])) {
			//check em
			$email = $_GET['em'];
			$result = $this->member->checkUserExistsByEmail($email);
			if (!$result) {
				flash("member_not_exists", null, 0);
			}else{
				$member_reg_auth = $G['setting']['new_userauth'];
				$id = $this->member->field("id", "email='".$email."'");
				$member_info = $this->member->getInfoById($id);
				require(LIB_PATH."sendmail.inc.php");
				require(CACHE_LANG_PATH."lang_emails.php");
				if ($member_reg_auth == 1) {
					$if_need_check = true;
					$exp_time = $this->member->timestamp+86400;
					$tmp_username = $member_info['username'];
					$hash = authcode("{$tmp_username}\t".$exp_time, "ENCODE");
					//$hash = str_replace(array("+", "|"), array("|", "_"), $hash);
					$hash = rawurlencode($hash);
					setvar("hash", $hash);
					setvar("expire_date", date("Y-m-d H:i",strtotime("+1 day")));
					$sended = pb_sendmail(array($email, $member_info['username']), $member_info['username'].",".$arrTemplate["_pls_active_your_account"], "activite");
					if (empty($G['setting']['reg_filename'])) {
						$gopage = URL.'register.php?action=done&em='.urlencode($email);
					}else{
						$gopage = URL.$G['setting']['reg_filename'].'?action=done&em='.urlencode($email);
					}
					pheader("location:".$gopage);
				}
			}
		}else{
			flash("invalid_request", null, 0);
		}
	}
	
	function getpasswd()
	{
		if (isset($_POST['do'])) {
			pb_submit_check('data');
			$do = trim($_POST['do']);
			$username = trim($_POST['data']['username']);
			$userpass = trim($_POST['data']['password1']);
			if (!empty($userpass) && !empty($username)) {
				$user_exists = $this->member->checkUserExist($username, true);
				if (!$user_exists) {
					flash("member_not_exists");
				}else{
					$result = $this->member->dbstuff->Execute("UPDATE {$this->member->table_prefix}members SET userpass='".$this->member->authPasswd($userpass)."' WHERE id=".$this->member->info['id']." AND status='1'");
					if ($result) {
						flash("reset_and_login", "logging.php");
					}
				}
			}
		}else{
			flash();
		}
	}
}
?>