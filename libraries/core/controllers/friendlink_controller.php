<?php
class Friendlink extends PbController {
	var $name = "Friendlink";
	
	function __construct()
	{
		$this->loadModel("friendlink");
	}
	
	function index()
	{
		global $viewhelper;
		$viewhelper->setPosition(L("apply_friendlink", "tpl"));
		formhash();
		render("friendlink");
	}
	
	function add()
	{
		global $smarty;
		using( "message");
		$pms = new Messages();
		if (isset($_POST['do']) && !empty($_POST['friendlink'])) {
			pb_submit_check('friendlink');
			$data = $_POST['friendlink'];
			$result = false;
			$data['status'] = 0;
			$data['created'] = $data['modified'] = $this->friendlink->timestamp;
			$result = $this->friendlink->save($data);
			if ($result) {
				$pms->SendToAdmin('', array(
				"title"=>$data['title'].L("apply_friendlink"),
				"content"=>$data['title'].L("apply_friendlink")."\n".$_POST['data']['email']."\n".$data['description'],
				));
				flash('wait_apply');
			}
		}else{
			flash();
		}
	}
}
?>