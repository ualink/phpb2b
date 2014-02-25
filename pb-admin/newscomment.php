<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
uses("newscomment");
require(LIB_PATH. 'page.class.php');
require("session_cp.inc.php");
$newscomment = new Newscomments();
$page = new Pages();
$conditions = array();
$tpl_file = "newscomment";
$amount = $newscomment->findCount(null, $conditions);
$page->setPagenav($amount);
$joins[] = "LEFT JOIN {$tb_prefix}newses n ON n.id=Newscomment.news_id";
$newscomment_list = $newscomment->findAll("Newscomment.id,Newscomment.news_id,Newscomment.message,Newscomment.cache_username as username,Newscomment.date_line AS pubdate,n.title", $joins, $conditions, "id DESC", $page->firstcount, $page->displaypg);
setvar("Items",$newscomment_list);
uaAssign(array("ByPages"=>$page->pagenav));
if (isset($_POST['del']) && is_array($_POST['id'])) {
	$deleted = $newscomment->del($_POST['id']);
	if (!$deleted) {
		flash();
	}
}
template($tpl_file);
?>