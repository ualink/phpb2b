<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2238 $
 */
require("../libraries/common.inc.php");
require("room.share.php");
require(LIB_PATH .'time.class.php');
uses("job", "typeoption", "area", "industry", "jobtype");
check_permission("job");
$job = new Jobs();
$area = new Areas();
$industry = new Industries();
$typeoption = new Typeoption();
$jobtypes = new Jobtypes();
$tpl_file = "job";
if (!$company->Validate($companyinfo)) {
	flash("pls_complete_company_info", "company.php", 0);
}
if (isset($_GET['do'])) {
	$do = trim($_GET['do']);
	if (isset($_GET['id'])) {
		$id = intval($_GET['id']);
	}
	if($do=="del" && !empty($id)){
		$job->del($id, "member_id=".$the_memberid);
	}
	if($do == "edit"){
		setvar("Genders", $G['typeoption']['gender']);
		setvar("Educations", $G['typeoption']['education']);
		setvar("Salary", $G['typeoption']['salary']);
		setvar("Worktype", $G['typeoption']['work_type']);
		setvar("JobtypeOptions", $jobtypes->getTypeOptions());
		if(!empty($id)){
			$res = $job->read("*", $id, null, "Job.member_id=".$the_memberid);
			if (empty($res)) {
				flash("action_failed");
			}
			$res['expire_date'] = df($res['expire_time']);
			$r1 = $industry->disSubOptions($res['industry_id'], "industry_");
			$r2 = $area->disSubOptions($res['area_id'], "area_");
			$res = am($res, $r1, $r2);
			setvar("item",$res);
		}
		$tpl_file = "job_edit";
		vtemplate($tpl_file);
		exit;
	}
}
if (!empty($_POST['job']) && $_POST['save']) {
	$vals = $_POST['job'];
	pb_submit_check('job');
	$now_job_amount = $job->findCount(null, "created>".$today_start." AND member_id=".$the_memberid);
	if (isset($_POST['id'])) {
		$id = $_POST['id'];
	}
	if(!empty($_POST['expire_time'])) {
		$vals['expire_time'] = Times::dateConvert($_POST['expire_time']);
	}
	$check_job_update = $g['job_check'];
	if ($check_job_update=="0") {
		$vals['status'] = 1;
        $message_info = 'msg_wait_success';
	}else {
		$vals['status'] = 0;
		$message_info = 'msg_wait_check';
	}
    $vals['industry_id'] = PbController::getMultiId($_POST['industry']['id']);
    $vals['area_id'] = PbController::getMultiId($_POST['area']['id']);
	if(!empty($id)){
		$vals['modified'] = $time_stamp;
		$result = $job->save($vals, "update", $id, null, "member_id=".$the_memberid);
	}else{
    	if ($g['max_job'] && $now_job_amount>=$g['max_job']) {
    		flash('one_day_max');
    	}
		$vals['created'] = $vals['modified'] = $time_stamp;
		$vals['company_id'] = $companyinfo['id'];
		$vals['member_id'] = $the_memberid;
		$vals['cache_spacename'] = $pdb->GetOne("SELECT space_name FROM {$tb_prefix}members WHERE id=".$the_memberid);
		$result = $job->save($vals);
	}
	if(!$result){
		flash();
	}else{
		flash($message_info);
	}
}
$result = $job->findAll("*", null, "Job.member_id=".$the_memberid, "id DESC", 0, 10);
if (!empty($result)) {
	for ($i=0; $i<count($result); $i++){
		$result[$i]['pubdate'] = df($result[$i]['created']);
		$result[$i]['expire_date'] = df($result[$i]['expire_time']);
	}
	setvar("Items",$result);
}
$job_status = explode(",",L('product_status', 'tpl'));
setvar("CheckStatus", $job_status);
setvar("Worktype", $G['typeoption']["work_type"]);
setvar("Salary", $G['typeoption']["salary"]);
vtemplate($tpl_file);
?>