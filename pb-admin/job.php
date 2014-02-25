<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
require(PHPB2B_ROOT.'libraries/page.class.php');
require("session_cp.inc.php");
uses("job","company","member","typeoption");
$job = new Jobs();
$page = new Pages();
$member = new Members();
$company = new Companies();
$typeoption = new Typeoption();
$conditions = null;
$table = array();
$job_status = explode(",",L('product_status', 'tpl'));
setvar("CheckStatus", $job_status);
$tpl_file = "job";
if (isset($_GET['do'])) {
	$do = trim($_GET['do']);
	if (!empty($_GET['id'])) {
		$id = intval($_GET['id']);
	}
	if ($do == "del" && !empty($id)) {
		$job->del($_GET['id']);
	}
	if ($do == "view" && !empty($id)) {
		$tpl_file = "job.view";
		$sql = "SELECT j.name,j.work_station,j.content,j.require_gender_id,j.peoples,j.require_education_id,j.require_age,j.salary_id,j.worktype_id,j.clicked,j.created,j.expire_time,c.name as cache_companyname,m.username as cache_username from {$tb_prefix}jobs as j LEFT JOIN {$tb_prefix}companies c ON j.company_id=c.id LEFT JOIN {$tb_prefix}members m ON j.member_id=m.id where j.id=".$id;
		$result = $pdb->GetRow($sql);
		setvar("item", $result);
		setvar("Genders", $typeoption->get_cache_type("gender"));
		setvar("Educations", $typeoption->get_cache_type('education'));
		setvar("Worktypes", $typeoption->get_cache_type('work_type'));
		setvar("SalaryLevels", $typeoption->get_cache_type('salary'));
		template($tpl_file);
		exit;
	}
}
if (isset($_POST['pb_action'])) {
	if (!empty($_POST['id'])) {
		if ($_POST['pb_action'] == "none" || array_key_exists($_POST['pb_action'], $job_status)) {
			$result = $job->saveField("status", intval($_POST['pb_action']), $_POST['id']);
		}elseif ($_POST['pb_action'] == "del"){
			$result = $job->del($_POST['id']);
		}
	}
}
if(isset($_POST['del'])){
	if(!empty($_POST['id'])){
		$job->del($_POST['id']);
	}
}
$fields = "Job.id,Job.name as jobname,Job.created,Job.status as jobstatus, c.name as companyname,m.username";
$sql = "SELECT count(id) AS Amount FROM {$tb_prefix}jobs";
$amount = $pdb->GetOne($sql);
$joins = "LEFT JOIN {$tb_prefix}companies c ON Job.company_id=c.id LEFT JOIN {$tb_prefix}members m ON Job.member_id=m.id";
$page->setPagenav($amount);
$sql = "SELECT ".$fields." FROM {$tb_prefix}jobs AS Job {$joins} ORDER BY Job.id DESC LIMIT $page->firstcount,$page->displaypg";
$result = $pdb->GetArray($sql);
if (!empty($result)) {
	for($i=0; $i<count($result); $i++){
		$result[$i]['pubdate'] = df($result[$i]['created']);
	}
	setvar("Items", $result);
}
uaAssign(array("ByPages"=>$page->pagenav));
template($tpl_file);
?>