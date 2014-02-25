<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2258 $
 */
require("../libraries/common.inc.php");
require(LIB_PATH .'time.class.php');
uses("trade","tag","tradefield","attachment","keyword","membertype","setting","typeoption","area","industry","meta");
require(PHPB2B_ROOT.'libraries/page.class.php');
require("session_cp.inc.php");
$attachment = new Attachment('pic');
$area = new Areas();
$meta = new Metas();
$industry = new Industries();
$setting = new Settings();
$membertype = new Membertypes();
$offer = new Tradefields();
$tag = new Tags();
$keyword = new Keywords();
$typeoption = new Typeoption();
$trade = new Trades();
$trade_controller = new Trade();
$tpl_file = "offer";
$conditions = array();
$page = new Pages();
$trade_names = $trade_controller->getTradeTypes();
setvar("TradeTypes", $trade_names);
setvar("CheckStatus", $check_status = explode(",",L('product_status', 'tpl')));
if (isset($_POST['batch_commend'])) {
	flash("success");
}
if (isset($_POST['refresh']) && !empty($_POST['id'])) {
	$result = $trade->refresh($_POST['id']);
	if (!$result) {
		flash();
	}else{
		flash("success");
	}
}
if (isset($_POST['export']) && !empty($_POST['id'])) {
	$result = $pdb->GetArray("SELECT * FROM {$tb_prefix}trades WHERE id IN (".implode(",", $_POST['id']).")");
	if (!empty($result)) {
		require_once(LIB_PATH. "excel_export.class.php");
		// generate file (constructor parameters are optional)
		$excel = new excel_xml();
		foreach ($result as $key=>$val) {
			$excel->add_row(array(
			$trade_names[$val['type_id']],
			$val['title'],
			$val['adwords'],
			htmlspecialchars(trim($val['content'], "\"")),
			$val['price'],
			$check_status[$val['status']],
			df($val['submit_time'], "Y-m-d"),
			df($val['expire_time'], "Y-m-d")
			));
		}
		$excel->create_worksheet(L("offer", "tpl"));
		$excel->download(date("YmdHis").'.xls');
	}
}
if (isset($_POST['commend'])) {
	if (!empty($_POST['id'])) {
		foreach ($_POST['id'] as $key=>$val) {
			$old_commend = $pdb->GetOne("select if_commend from {$tb_prefix}trades where id=".$val);
			$result = ($old_commend==1)?$pdb->Execute("update {$tb_prefix}trades set if_commend=0 where id={$val}"):$pdb->Execute("update {$tb_prefix}trades set if_commend=1 where id={$val}");
		}
	}
	if ($result) {
		flash("success");
	}else{
		flash();
	}
}
if (isset($_POST['setting'])) {
	require(LIB_PATH. "cache.class.php");
	$cache = new Caches();
	$updated = $setting->replace($_POST['data']['setting'], 1);
	if($updated){
		$cache->writeCache("setting", "setting");
		pheader("location:offer.php?do=setting");
	}else{
		flash();
	}
}
if(isset($_GET['do'])){
	$do = trim($_GET['do']);
	if (!empty($_GET['id'])) {
		$id = intval($_GET['id']);
	}
	if ($do == "refresh" && !empty($id)) {
		$trade->refresh($id);
	}
	if ($do == "setting") {
		$setting_offer_status = L("setting_offer_expired", "tpl");
		$setting_offer_status = explode("|", $setting_offer_status);
		setvar("SettingStatus", $setting_offer_status);
		setvar("AskAction", $typeoption->get_cache_type("common_option"));
		$item = $setting->getValues(1);
		setvar("item", $item);
		$tpl_file = "offer.setting";
		template($tpl_file);
		exit;
	}
	if ($do=="edit") {
		$result = $membertype->findAll("id,name",null, $conditions, " id desc");
		$user_types = array();
		foreach ($result as $key=>$val) {
			$user_types[$val['id']] = $val['name'];
		}
		setvar("Membertypes", $user_types);
		foreach ($viewhelper->colorarray as $color) {
			$colors[] = '"'.substr($color, 1).'"';
		}
		setvar("colors", implode(",", $colors));
		if (!empty($id)) {
			$sql = "SELECT t.*,tf.*,m.username,c.name as companyname FROM {$tb_prefix}trades t LEFT JOIN {$tb_prefix}tradefields tf ON t.id=tf.trade_id LEFT JOIN {$tb_prefix}members m ON t.member_id=m.id LEFT JOIN {$tb_prefix}companies c ON c.id=t.company_id WHERE t.id={$id}";
			$res = $pdb->GetRow($sql);
			if (isset($res['picture'])) {
				$res['image'] = pb_get_attachmenturl($res['picture'], '../', 'small');
			}
			$res['pubdate'] = df($res['submit_time']);
			$res['expdate'] = df($res['expire_time']);
			$highlight_style = parse_highlight($res['highlight'], true);
			setvar("hl", $highlight_style);
			if (empty($res)) {
				flash();
			}else{
				$tag->getTagsByIds($res['tag_ids'], true);
				$res['tag'] = $tag->tag;
				$r1 = $industry->disSubOptions($res['industry_id'], "industry_");
				$r2 = $area->disSubOptions($res['area_id'], "area_");
				$seo = $meta->getSEOById($id, 'trade');
				$res = am($res, $r1, $r2, $seo);
				setvar("item",$res);
			}
		}
		$tpl_file = "offer.edit";
		template($tpl_file);
		exit;
	}
	if ($do == "search") {
		if (!empty($_GET['display_pg']) && in_array($_GET['display_pg'], $page->page_option)) {
			$page->displaypg = $_GET['display_pg'];
		}
		if($_GET['type_id']>0){ 
            $conditions[] = "Trade.type_id='".$_GET['type_id']."'";
		}
		if (!empty($_GET['q'])) {
			$conditions[]= "Trade.title like '%".trim($_GET['q'])."%'";
		}
		if (isset($_GET['status']) && $_GET['status']>=0) {
			$conditions[]= "Trade.status='".$_GET['status']."'";
		}
		if (!empty($_GET['adwords'])) {
			$conditions[]= "Trade.adwords like '%".trim($_GET['adwords'])."%'";
		}
		if (!empty($_GET['username'])) {
			$conditions[] = "m.username like '%".$_GET['username']."%'";
		}
		if (isset($_GET['PubFromDate'])) {	
			if ($_GET['PubFromDate']!="None" && $_GET['PubToDate']!="None") {
				$condition= "Trade.created BETWEEN ";
				$condition.= Times::dateConvert($_GET['PubFromDate']);
				$condition.= " AND ";
				$condition.= Times::dateConvert($_GET['PubToDate']);
				$conditions[] = $condition;
			}
		}
		if (isset($_GET['ExpFromDate'])) {	
			if ($_GET['ExpFromDate']!="None" && $_GET['ExpToDate']!="None") {
				$condition= "Trade.expire_time BETWEEN ";
				$condition.= Times::dateConvert($_GET['ExpFromDate']);
				$condition.= " AND ";
				$condition.= Times::dateConvert($_GET['ExpToDate']);
				$conditions = $condition;
			}
		}
		if(!empty($_GET['ip'])){
			$conditions[]="Trade.ip_addr='".$_GET['ip']."'";
		}
	}
}
if (isset($_POST['urgent_batch'])) {
	$ids = implode(",",$_POST['id']);
	$result = $pdb->Execute("update ".$trade->getTable()." set if_urgent='1' where if_urgent='0' AND id in (".$ids.")");
	if (!$result) {
		flash();
	}
}
if (isset($_POST['cancel_urgent_batch'])) {
	$ids = implode(",",$_POST['id']);
	$result = $pdb->Execute("update ".$trade->getTable()." set if_urgent='0' where if_urgent='1' AND id in (".$ids.")");
	if (!$result) {
		flash();
	}
}
if(isset($_POST['del']) && !empty($_POST['id'])){
    foreach ($_POST['id'] as $val) {
    	$picture = $trade->field("picture", "id=".$val);
    	$attachment->deleteBySource($picture);
    }
	$result = $trade->Delete($_POST['id']);
	if (!$result) {
		flash();
	}
}
if(isset($_POST['up_batch'])) {
	$result = $trade->check($_POST['id'],1);
	if (!$result) {
		flash("trade.php");
	}
}
if(isset($_POST['down_batch'])) {
	$result = $trade->check($_POST['id'],0);
	if (!$result) {
		flash();
	}
}
if (isset($_POST['status_batch']) && ($_POST['status_batch']>=0)) {
	if(!empty($_POST['id'])){
		$tmp_to = intval($_POST['status_batch']);
		$result = $trade->check($_POST['id'], $tmp_to);
	}
}
if(isset($_POST['pass'])){
	$tid = (isset($_POST['id']))?$_POST['id']:null;
	$sql = "update ".$trade->getTable()." set status='1' where id=".$tid;
	$result = $pdb->Execute($sql);
	if (!$result) {
		flash();
	}
}
if(isset($_POST['forbid'])){
	$tid = (isset($_POST['id']))?$_POST['id']:null;
	$sql = "update ".$trade->getTable()." set status='0' where id=".$tid;
	$result = $pdb->Execute($sql);
	if (!$result) {
		flash("trade.php");
	}
}
if(isset($_POST['save'])){
	$_POST['data']['trade']['title'] = pb_lang_merge($_POST['data']['multi']);
	$_POST['data']['trade']['content'] = pb_lang_merge($_POST['data']['multita']);
	$vals = $_POST['data']['trade'];
	if($vals['title']==''){
		flash();
	}
	if(isset($_POST['id'])){
		$id = intval($_POST['id']);
	}
	if (isset($_POST['data']['company_name'])) {
		if (!pb_strcomp($_POST['data']['company_name'], $_POST['company_name'])) {
			$vals['company_id'] = $pdb->GetOne("SELECT id FROM {$tb_prefix}companies WHERE name='".$_POST['data']['company_name']."'");
		}
	}
	if (isset($_POST['data']['username'])) {
		if (!pb_strcomp($_POST['data']['username'], $_POST['username'])) {
			$vals['member_id'] = $pdb->GetOne("SELECT id FROM {$tb_prefix}members WHERE username='".$_POST['data']['username']."'");
		}
	}
	if(isset($_POST['submittime'])){
		if(!empty($_POST['submittime'])) {
		    $vals['submit_time'] = Times::dateConvert($_POST['submittime']);
		}
	}else{
		$vals['submit_time'] = $time_stamp;
	}
	if(!empty($_POST['expiretime'])) {
		$vals['expire_time'] = Times::dateConvert($_POST['expiretime']);
	}
	$attachment->rename_file = "offer-".$time_stamp;
	if(!empty($id)){
		$attachment->rename_file = "offer-".$id;
	}	
	if (!empty($_FILES['pic']['name'])) {
		$attachment->upload_process();
		$vals['picture'] = $attachment->file_full_url;
	}
	if (!empty($vals['content'])) {
		$vals['content'] = stripcslashes($vals['content']);
	}
	if(!empty($_POST['require_membertype']) && !in_array(0, $_POST['require_membertype'])){
		$reses = implode(",", $_POST['require_membertype']);
		$vals['require_membertype'] = $reses;
	}elseif(!empty($_POST['require_membertype'])){
		$vals['require_membertype'] = 0;
	}	
	$vals['tag_ids'] = $tag->setTagId($_POST['data']['tag']);
    $vals['industry_id'] = PbController::getMultiId($_POST['industry']['id']);
    $vals['area_id'] = PbController::getMultiId($_POST['area']['id']);
    //$_POST['highlight']['style']['bold'] = 0;
    //$_POST['highlight']['style']['italic'] = 0;
    //$_POST['highlight']['style']['underline'] = 0;
    $highlight_style = $_POST['highlight']['style'];//array
    $highlight_color = array_search(strtoupper($_POST['highlight']['color']), $viewhelper->colorarray);
    $stylebin = '';
    for($i = 1; $i <= 3; $i++) {
    	$stylebin .= empty($highlight_style[$i]) ? '0' : '1';
    }
    $highlight_style = bindec($stylebin);
    if($highlight_style < 0 || $highlight_style > 7 || $highlight_color < 0 || $highlight_color > 8) {
    	;
    }else{
    	$highlight_style = $highlight_style.$highlight_color;
    	$vals['highlight'] = $highlight_style;
    }
	if (!empty($id)) {
		$vals['modified'] = $time_stamp;
		$updated = $trade->save($vals, "update", $id);
	}else {
		$vals['submit_time'] = (empty($vals['submit_time']))?$time_stamp:$vals['submit_time'];
		$vals['expire_time'] = (empty($vals['expire_time']))?($time_stamp+60*60*24*30):$vals['expire_time'];
		$vals['created'] = $vals['modified'] = $time_stamp;
		$updated = $trade->save($vals);
		$last_insert_key = "{$tb_prefix}trades_id";
    	$id = $trade->$last_insert_key;
	}
	//seo info
	$meta->save('trade', $id, $_POST['data']['meta']);
	if (!$updated) {
		flash();
	}else{
		if($G['setting']['keyword_bidding']) {
			$keyword->setIds($vals['title'].$vals['content'], 'trades', true, $id);
		}
		flash("success", "offer.php?do=search&page=".$_REQUEST['page']."&type_id=".$vals['type_id']);
	}
}
$amount = $trade->findCount(null, $conditions,"Trade.id");
$page->setPagenav($amount);
$fields = "Trade.member_id,m.username,Trade.company_id,Trade.adwords,Trade.highlight,Trade.type_id,Trade.status,Trade.id,Trade.title,Trade.clicked,Trade.if_urgent,Trade.submit_time AS pubdate,Trade.submit_time,Trade.modified,Trade.expire_time AS expdate,Trade.expire_time,Trade.picture as TradePicture,require_point,require_membertype,ip_addr as IP,Trade.if_commend";
$joins[] = "LEFT JOIN {$tb_prefix}members m ON m.id=Trade.member_id";
$result = $trade->findAll($fields,$joins, $conditions,"Trade.id DESC",$page->firstcount,$page->displaypg);
if (!empty($result)) {
	for($i=0; $i<count($result); $i++){
		$result[$i]['pubdate'] = df($result[$i]['pubdate']);
		$result[$i]['moddate'] = df($result[$i]['submit_time'], "Y-m-d H:i");
		$result[$i]['expdate'] = df($result[$i]['expdate']);
		$result[$i]['style'] = parse_highlight($result[$i]['highlight']);
		if ($result[$i]['expire_time']<$time_stamp) {
			$result[$i]['if_expire'] = L("has_expired", "tpl");
		}
	}
	setvar("Items", $result);
}
setvar("ByPages", $page->getPagenav());
setvar("TradeNames", $trade_controller->getTradeTypeNames());
template($tpl_file);
?>