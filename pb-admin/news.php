<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2258 $
 */
require("../libraries/common.inc.php");
uses("news","newstype", "membertype","attachment", "tag","typeoption","meta");
require(LIB_PATH .'time.class.php');
require(PHPB2B_ROOT.'libraries/page.class.php');
require("session_cp.inc.php");
$tag = new Tags();
$page = new Pages();
$meta = new Metas();
$attachment = new Attachment('pic');
$typeoption = new Typeoption();
$membertype = new Membertypes();
$news = new Newses();
$newstype = new Newstypes();
$conditions = array();
$fields = null;
$tpl_file = "news";
setvar("AskAction", $typeoption->get_cache_type("common_option"));
if (isset($_GET['do'])) {
	$do = trim($_GET['do']);
	if (isset($_GET['action'])) {
		$action = trim($_GET['action']);
	}
	if (!empty($_GET['id'])) {
		$id = intval($_GET['id']);
	}
	if ($do == "search") {
		if (isset($_GET['keywords'])) $conditions[]= "News.keywords like '%".trim($_GET['news']['keywords'])."%'";
		if (isset($_GET['source'])) $conditions[]= "News.source like '%".trim($_GET['news']['source'])."%'";
		if (isset($_GET['q'])) $conditions[]= "News.title like '%".trim($_GET['q'])."%'";
		if (!empty($_GET['typeid'])) {
			$conditions[]= "News.type_id=".$_GET['typeid'];
		}
		if (isset($_GET['topicid'])) {
			setvar("Items", $pdb->GetArray("SELECT n.* FROM {$tb_prefix}topicnews tn RIGHT JOIN {$tb_prefix}newses n ON tn.news_id=n.id WHERE tn.topic_id=".intval($_GET['topicid'])));
			setvar("Newstypes", $newstype->getCacheTypes());
			template($tpl_file);
			exit;
		}
	}
	if ($do == "del" && !empty($id)) {
		$sql = "SELECT picture FROM {$tb_prefix}newses WHERE id=".$id;
		$attach_filename = $pdb->GetOne($sql);
		$news->del($id);
		$attachment->deleteBySource($attach_filename);
	}
	if ($do == "edit") {
		$news_info = null;
		$_PB_CACHE['area'] = cache_read("area");
		$_PB_CACHE['industry'] = cache_read("industry");
		setvar("CacheAreas", $_PB_CACHE['area']);
		setvar("CacheIndustries", $_PB_CACHE['industry']);		
		$result = $membertype->findAll("id,name",null, $conditions, " id desc");
		$user_types = array();
		foreach ($result as $key=>$val) {
			$user_types[$val['id']] = $val['name'];
		}
		setvar("Membertypes", pb_lang_split_recursive($user_types));
		$_newstypes = $newstype->getTypeOptions();
		setvar("NewstypeOptions", $_newstypes);
		if(!empty($id)){
			$item_info = $news->read("*",$id);
			if(($item_info['picture'])) $item_info['image'] = pb_get_attachmenturl($item_info['picture'], "../", 'small');
			$tag->getTagsByIds($item_info['tag_ids'], true);
			$item_info['tag'] = $tag->tag;
		}
		if ($action == "convert") {
			if (!empty($_GET['companynewsid'])) {
				$item_info['title'] = $pdb->GetOne("SELECT title FROM {$tb_prefix}companynewses WHERE id=".intval($_GET['companynewsid']));
			}
		}
		if (!empty($item_info)) {
			$seo = $meta->getSEOById($id, 'news');
			$item_info = am($item_info, $seo);
			setvar("item",$item_info);
		}
		$tpl_file = "news.edit";
		template($tpl_file);
		exit;
	}	
}
if (isset($_POST['update']) && !empty($_POST['if_focus'])) {
	$pdb->Execute("UPDATE ".$news->getTable()." SET if_focus=0");
	$pdb->Execute("UPDATE ".$news->getTable()." SET if_focus=1 WHERE id=".intval($_POST['if_focus']));
}
if (isset($_POST['focus']) && !empty($_POST['id'])) {
	$ids = implode(",", $_POST['id']);
	if($ids) $pdb->Execute("UPDATE ".$news->getTable()." SET if_focus=if_focus+1 WHERE id IN (".$ids.")");
}
if (isset($_POST['cancel_focus']) && !empty($_POST['id'])) {
	$ids = implode(",", $_POST['id']);
	$pdb->Execute("UPDATE ".$news->getTable()." SET if_focus=0 WHERE id IN (".$ids.")");
}
if (isset($_POST['del']) && is_array($_POST['id'])) {
	foreach ($_POST['id'] as $key=>$val){
	    $attach_filename = $pdb->GetOne("SELECT picture FROM {$tb_prefix}newses WHERE id=".$val);
	    $attachment->deleteBySource($attach_filename);
	}
	$deleted = $news->del($_POST['id']);
	if (!$deleted) {
		flash();
	}
}

if (isset($_POST['commend']) && is_array($_POST['id'])) {
	$news->saveField("if_commend", 1, $_POST['id']);
	flash("success");
}

if (isset($_POST['cancel_commend']) && is_array($_POST['id'])) {
	$news->saveField("if_commend", 0, $_POST['id']);
	flash("success");
}
if (isset($_POST['do']) && !empty($_POST['data']['news'])) {
	if (isset($_POST['id'])) {
		$id = intval($_POST['id']);
	}
	$attachment->if_orignal = false;
	$attachment->if_watermark = false;
	$attachment->if_thumb_middle = true;
	$vals = array();
	$vals = $_POST['data']['news'];
	if(!empty($_POST['require_membertype']) && !in_array(0, $_POST['require_membertype'])){
		$reses = implode(",", $_POST['require_membertype']);
		$vals['require_membertype'] = $reses;
	}elseif(!empty($_POST['require_membertype'])){
		$vals['require_membertype'] = 0;
	}
	$vals['tag_ids'] = $tag->setTagId($_POST['data']['tag']);
	if ($_POST['is_draft']==1) {
		$vals['status'] = 0;
	}
	if ($vals['start_time']!="0000-00-00" && $vals['start_time']>date("Y-m-d")) {
		$vals['status'] = 0;
	}
	if(empty($vals['start_time']))
	$vals['start_time'] = '0000-00-00';
	if(empty($vals['end_time']))
	$vals['end_time'] = '0000-00-00';
	if(!empty($id)){
		$vals['modified'] = $time_stamp;
		if (!empty($_FILES['pic']['name'])) {
			$attachment->rename_file = "news-".$id;	
			$attachment->insert_new = false;
			$attachment->upload_process();
			$vals['picture'] = $attachment->file_full_url;
		}
		$result = $news->save($vals, "update", $id);
	}else{
		$vals['created'] = $vals['modified'] = $time_stamp;
		if (!empty($_FILES['pic']['name'])) {
			$attachment->rename_file = "news-".($news->getMaxId()+1);	
			$attachment->upload_process();
			$vals['picture'] = $attachment->file_full_url;
		}
		$result = $news->save($vals);
		$last_insert_key = "{$tb_prefix}newses_id";
    	$id = $news->$last_insert_key;
	}
	//seo info
	$meta->save('news', $id, $_POST['data']['meta']);
	if (!$result) {
		flash();
	}
}
$amount = $news->findCount(null, $conditions);
$page->setPagenav($amount);
$result = $news->findAll("*", null, $conditions, "id DESC ",$page->firstcount,$page->displaypg);
if (!empty($result)) {
	for ($i=0; $i<count($result); $i++){
		$t1 = intval(str_replace("-", "", $result[$i]['start_time']));
		$t2 = intval(str_replace("-", "", $result[$i]['end_time']));
		if ($t1==0) {
			$result[$i]['start_time'] = L("effective_now", "tpl");
		}
		if ($t2==0) {
			$result[$i]['end_time'] = L("permanent_effective", "tpl");
		}
	}
}
setvar("Items", $result);
uaAssign(array("ByPages"=>$page->pagenav, "Newstypes"=>$newstype->getCacheTypes()));
template($tpl_file);
?>