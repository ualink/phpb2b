<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
uses("topic", "attachment");
require(LIB_PATH. 'page.class.php');
require("session_cp.inc.php");
$page = new Pages();
$attachment = new Attachment('pic');
$conditions = array();
$tpl_file = "newstopic";
$topic = new Topics();
if (isset($_POST['del']) && is_array($_POST['id'])) {
	$deleted = $topic->del($_POST['id']);
	if (!$deleted) {
		flash();
	}
}
if (isset($_POST['save']) && !empty($_POST['newstopic']['title'])) {
	$vals = array();
	$vals = $_POST['newstopic'];
	if (isset($_POST['id'])) {
		$id = intval($_POST['id']);
	}
	if (!empty($_FILES['pic']['name'])) {
		$attachment->rename_file = "newstopic-".$time_stamp;
		$attachment->insert_new = false;
		$attachment->if_watermark = false;
		$attachment->upload_process();
		$vals['picture'] = $attachment->file_full_url;
	}
	if (!empty($id)) {
		$vals['modified'] = $time_stamp;
		$pdb->Execute("DELETE FROM {$tb_prefix}topicnews WHERE topic_id=".$id);
		$topic->addNews($id, $_POST['data']['news']);
		$result = $topic->save($vals, "update", $id);
	}else{
		$vals['created'] = $vals['modified'] = $time_stamp;
		$result = $topic->save($vals);
		$key = $topic->table_name."_id";
		$topic->addNews($topic->$key, $_POST['data']['news']);
	}
	if (!$result) {
		flash();
	}else{
		flash("success");
	}
}
if (isset($_GET['do'])) {
	$do = trim($_GET['do']);
	if (!empty($_GET['id'])) {
		$id = intval($_GET['id']);
	}
	if($do == "del" && !empty($id)){
		$topic->del($id);
	}
	if ($do == "edit") {
		if(!empty($id)){
			$res = $topic->read("*",$id);
			$newses = $pdb->GetArray("SELECT news_id FROM {$tb_prefix}topicnews WHERE topic_id=".$res['id']);
			if (!empty($newses)) {
				$tmp_str = array();
				foreach ($newses as $key=>$val){
					$tmp_str[] = $val['news_id'];
				}
				$res['news'] = implode("\n", $tmp_str);
			}
			if (!empty($res['picture'])) {
				$res['image'] = pb_get_attachmenturl($res['picture'], "../");
			}
			setvar("item",$res);
		}
		$tpl_file = "newstopic.edit";
		template($tpl_file);
		exit;
	}
}
$amount = $topic->findCount(null, $conditions);
$page->setPagenav($amount);
$result = $topic->findAll("*", null, $conditions, "id DESC", $page->firstcount, $page->displaypg);
if (!empty($result)) {
	for($i=0; $i<count($result); $i++){
		if (!empty($result[$i]['picture'])) {
			$result[$i]['image'] = pb_get_attachmenturl($result[$i]['picture'], "../");
		}
	}
}
setvar("Items", $result);
uaAssign(array("ByPages"=>$page->pagenav));
template($tpl_file);
?>