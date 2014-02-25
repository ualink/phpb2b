<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2258 $
 */
require("../libraries/common.inc.php");
require("session_cp.inc.php");
uses("member","membergroup","typeoption");
require(LIB_PATH. 'time.class.php');
require(PHPB2B_ROOT. 'libraries/page.class.php');
require(CACHE_COMMON_PATH."cache_type.php");
$G['membergroup'] = cache_read("membergroup");
$G['trusttype'] = cache_read("trusttype");
$typeoption = new Typeoption();
$membergroup = new Membergroup();
$member = new Members();
$page = new Pages();
$tpl_file = "member";
$conditions = array();
setvar("MembergroupOptions", $membergroup->getUsergroups());
setvar("Membergroups", $member_groups = $G['membergroup']);
setvar("Membertypes", $member_types = $_PB_CACHE['membertype']);
$genders = $typeoption->get_cache_type("gender");
foreach($G['trusttype'] as $key=>$val){
	$tmp_trusttypes[$key] = $val['name'];
}
setvar("Trusttypes", $tmp_trusttypes);
if (isset($_POST)) {
	if (isset($_POST['del'])) {
	      $member->Delete($_POST['id']);
		  flash("success");
	}
	if (isset($_POST['check_in'])){
		$vals['status'] = 1;
		if(!$member->save($vals, "update", $member_id)){
			flash();
		}
	}
	if (isset($_POST['check_out'])){
		$vals['status'] = 0;
		if(!$member->save($vals, "update", $member_id)){
			flash();
		}
	}
	if (isset($_POST['export']) && !empty($_POST['id'])) {
		$result = $pdb->GetArray("SELECT m.*,mf.first_name,mf.last_name,mf.gender,mf.tel,mf.fax,mf.mobile,mf.address,mf.zipcode FROM {$tb_prefix}members m LEFT JOIN {$tb_prefix}memberfields mf ON m.id=mf.member_id WHERE m.id IN (".implode(",", $_POST['id']).")");
		if (!empty($result)) {
			require_once(LIB_PATH. "excel_export.class.php");
			$excel = new excel_xml();
			$header_style = array(
			'bold'       => 1,
			'size'       => '10',
			'color'      => '#FFFFFF',
			'bgcolor'    => '#4F81BD'
			);
			$excel->add_style('header', $header_style);
			$excel->add_row(array(
			'User Name',
			'Email',
			'Member Type',
			'Member Group',
			'True Name',
			'Gender',
			'Telephone',
			'Fax',
			'Mobile',
			'Address',
			'Zipcode'
			), 'header');
			foreach ($result as $key=>$val) {
				$excel->add_row(array(
				$val['username'],
				$val['email'],
				$member_types[$val['membertype_id']],
				$member_groups[$val['membergroup_id']]['name'],
				$val['first_name'].$val['last_name'],
				$genders[$val['gender']],
				$val['tel'],
				$val['fax'],
				$val['mobile'],
				$val['address'],
				$val['zipcode']
				));
			}
			$excel->create_worksheet(L("member", "tpl"));
			$excel->download(L("member", "tpl").date("YmdHis").'.xls');
		}
	}	
}

if (isset($_POST['pb_action']) && !empty($_POST['id'])) {
	list($action_name, $action_id) = explode("_", $_POST['pb_action']);
	$ids = "(".implode(",", $_POST['id']).")";
	switch ($action_name) {
		case "status":
			$sql = "UPDATE {$tb_prefix}members SET status='".$action_id."' WHERE id IN ".$ids;
			break;
		case "membertype":
			$sql = "UPDATE {$tb_prefix}members SET membertype_id='".$action_id."' WHERE id IN ".$ids;
			break;
		case "membergroup":
			$sql = "UPDATE {$tb_prefix}members SET membergroup_id='".$action_id."' WHERE id IN ".$ids;
			break;
		default:
			break;
	}
	$result = $pdb->Execute($sql);
	if (!$result) {
		flash();
	}
}
if (isset($_POST['save'])) {
	if (isset($_POST['id'])) {
		$member_id = $_POST['id'];
	}
	$vals = $_POST['data']['member'];
	if(!empty($_POST['data']['userpass'])) {
		if (!pb_strcomp($_POST['data']['userpass'],$_POST['data']['re_userpass'])) {
			flash("invalid_password");
		}else{
			$vals['userpass'] = $member->authPasswd($_POST['data']['userpass']);
		}
	}
		if (!empty($_POST['data']['trusttype'])) {
			$vals['trusttype_ids'] = implode(",", $_POST['data']['trusttype']);
		}
	if (isset($_POST['data']['service_start_date'])) {
		$vals['service_start_date'] = Times::dateConvert($_POST['data']['service_start_date']);
	}	
	if (isset($_POST['data']['service_end_date'])) {
		$vals['service_end_date'] = Times::dateConvert($_POST['data']['service_end_date']);
	}
	if(!empty($member_id)){
		$vals['modified'] = $time_stamp;
		if (!empty($vals['space_name'])) {
			$member->updateSpaceName(array('id'=>$member_id), $vals['space_name']);
		}
		$result = $member->save($vals, "update", $member_id);
	}else{
		$vals['status'] = 1;
		$vals['created'] = $vals['modified'] = $time_stamp;
		$result = $member->save($vals);
	}
	if(!$result){
		flash();
	}
}
if (isset($_GET['do'])) {
	$do = trim($_GET['do']);
	if (isset($_GET['id'])) {
		$id = intval($_GET['id']);
	}
	if ($do == "edit") {
		$vals =  null;
		if (!empty($id)){
			$res = $pdb->GetRow("SELECT m.*,mf.* FROM {$tb_prefix}members m LEFT JOIN {$tb_prefix}memberfields mf ON m.id=mf.member_id WHERE m.id={$id}");
			if (empty($res)) {
				flash("data_not_exists");
			}
			if (!empty($res['trusttype_ids'])) {
				$tmp_user_trusttype = explode(",", $res['trusttype_ids']);
				$res['selected_trusttypeid'] = $tmp_user_trusttype;
				unset($tmp_user_trusttype);
			}
			if (!empty($res['membergroup_id'])) {
				$res['groupimage'] = URL.STATICURL. "images/group/".$G['membergroup'][$res['membergroup_id']]['avatar'];
			}
			if($res['service_start_date']) $res['service_start_date'] = df($res['service_start_date']);
			if($res['service_end_date']) $res['service_end_date'] = df($res['service_end_date']);
			setvar("item", $res);
		}
		uaAssign(array("Genders"=> $genders));
		setvar("MemberStatus", $typeoption->get_cache_type("check_status"));
		$tpl_file = "member.edit";
		template($tpl_file);
		exit;
	}
	if ($do == "search") {
		if(!empty($_GET['member']['name'])) $conditions[] = "Member.username like '%".$_GET['member']['name']."%'";
		if(isset($_GET['member']['status']) && $_GET['member']['status']>=0) $conditions[] = "Member.status='".$_GET['member']['status']."'";
		if ($_GET['groupid']>0) {
			$conditions[] = "Member.membergroup_id=".intval($_GET['groupid']);
		}
	}
	if ($do=="del" && !empty($id)) {
		$member->Delete($id);
	}
}
$fields = "id,username,CONCAT(mf.first_name,mf.last_name) AS NickName,mf.reg_ip,last_ip,points,credits,membergroup_id,status,created AS pubdate,last_login,trusttype_ids";
$amount = $member->findCount(null, $conditions);
$page->setPagenav($amount);
$joins[] = "LEFT JOIN {$tb_prefix}memberfields mf ON Member.id=mf.member_id";
$result = $member->findAll($fields, $joins, $conditions, "Member.id DESC ",$page->firstcount,$page->displaypg);
if (!empty($result)) {
	for($i=0; $i<count($result); $i++){
		$tmp_img = null;
		if ($result[$i]['id']!=$administrator_id) {
			$result[$i]['candelete'] = 1;
		}else{
			$result[$i]['candelete'] = 0;
		}
		if (!empty($result[$i]['trusttype_ids'])) {
			$tmp_str = explode(",", $result[$i]['trusttype_ids']);
			foreach ($tmp_str as $key=>$val){
				$tmp_img.="<img src='".URL.STATICURL. "images/icon/".$G['trusttype'][$val]['avatar']."' alt='".$G['trusttype'][$val]['name']."' />";
			}
			$result[$i]['trust_image'] = $tmp_img;
		}
		if (!empty($result[$i]['membergroup_id'])) {
			$result[$i]['group_image'] = URL.STATICURL. "images/group/".$member_groups[$result[$i]['membergroup_id']]['avatar'];
		}
	}
	setvar("Items", $result);
}
uaAssign(array("MemberStatus"=> $typeoption->get_cache_type("check_status"),"ByPages"=>$page->pagenav));
template($tpl_file);
?>