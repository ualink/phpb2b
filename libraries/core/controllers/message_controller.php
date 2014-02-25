<?php
class Message extends PbController {
	var $name = "Message";
	
	function __construct()
	{
		$this->loadModel("message");
	}
	
	function add()
	{
		global $pb_user, $smarty, $administrator_id;
		if (isset($_POST['companyid']) && !empty($_POST['feed']) && !empty($pb_user['pb_userid'])) {
			$vals = $_POST['feed'];
			$vals['created'] = $this->message->timestamp;
			$vals['status'] = 0;
			$vals['from_member_id'] = $pb_user['pb_userid'];
			$vals['cache_from_username'] = $pb_user['pb_username'];
			$member_id = $this->message->GetOne("SELECT member_id FROM {$this->message->table_prefix}companies WHERE id=".intval($_POST['companyid']));
			if (empty($member_id)) {
				$vals['to_member_id'] = $administrator_id;
				$vals['cache_to_username'] = $this->message->GetOne("SELECT username FROM {$this->message->table_prefix}members WHERE id=".$administrator_id);
			}else{
				$member_info = $this->message->GetRow("SELECT id,username FROM {$this->message->table_prefix}members WHERE id=".$member_id);
				$vals['to_member_id'] = $member_info['id'];
				$vals['cache_to_username'] = $member_info['username'];
			}
			$vals['title'] = L("pms_from_space", "tpl");
			if($this->message->save($vals)){
				$smarty->flash('feedback_already_submit', null, 0);
			}
		}
	}
}
?>