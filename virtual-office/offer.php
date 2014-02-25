<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2223 $
 */
require("../libraries/common.inc.php");
require("room.share.php");
require(PHPB2B_ROOT.'libraries/page.class.php');
check_permission("offer");
$tpl_file = "offer";
$page = new Pages();
uses("trade", "tradefield", "product","tag","attachment", "form", "typeoption", "point", "industry", "area");
$attachment = new Attachment("pic");
$area = new Areas();
$industry = new Industries();
$form = new Forms();
$point = new Points();
$tradefield = new Tradefields();
$tag = new Tags();
$trade = new Trades();
$trade_controller = new Trade();
$typeoption = new Typeoption();
$conditions = array();
//$conditions[]= "member_id = ".$the_memberid;
setvar("TradeTypes", $trade_controller->getTradeTypes());
setvar("TradeNames", $trade_controller->getTradeTypeNames());
$tmp_personalinfo = $memberinfo;
setvar("MemberInfo", $tmp_personalinfo);
$expires = $trade_controller->getOfferExpires();
setvar("TradeTypes", $trade_controller->getTradeTypes());
setvar("PhoneTypes", $G['typeoption']["phone_type"]);
setvar("ImTypes", $G['typeoption']["im_type"]);
setvar("OfferExpires",$expires);
setvar("Countries", $countries = cache_read("country"));
if(isset($company_id))
setvar("CompanyId", $company_id);
$tMaxDay = (!empty($G['setting']['offer_refresh_lower_day']))?$G['setting']['offer_refresh_lower_day']:3;
$tMaxHours = (!empty($G['setting']['offer_update_lower_hour']))?$G['setting']['offer_update_lower_hour']:24;
$prod_info = array();
$conditions[] = "member_id='".$the_memberid."'";
if(!empty($company_info)){
    $tmp_personalinfo['MemberTel'] = $company_info['tel'];
    $tmp_personalinfo['ContactEmail'] = $company_info['email']?$company_info['email']:$tmp_personalinfo['email'];
}
if (isset($_GET['typeid'])) {
	$conditions[] = "type_id='".intval($_GET['typeid'])."'";
}
if (isset($_GET['do'])) {
	$do = trim($_GET['do']);
	if (isset($_GET['id'])) {
		$id = intval($_GET['id']);
	}
	switch ($do) {
		case "moderate":
			$tomorrow_start = $today_start+86400;
			$tomorrow_end = $today_start+172800;
			if($memberinfo['points']<$G['setting']['offer_moderate_point']){
				flash("not_enough_point");
			}
			$point->actions['moderate'] = array("rule"=>"every","do"=>"dec","point"=>$G['setting']['offer_moderate_point']);
			if (!empty($id)) {
				$item = $pdb->GetRow("SELECT * FROM {$tb_prefix}trades WHERE id=".$id." AND status=1 AND expire_time>".$time_stamp." AND member_id=".$the_memberid);
				if (empty($item)) {
					flash("has_been_expired");
				}
//				if ($item['display_expiration']>$tomorrow_start && $item['display_expiration']<$tomorrow_end) {
//					flash("one_time_within_24_h");
//				}
				$sql = "UPDATE {$tb_prefix}trades SET display_order='1',display_expiration='".($time_stamp+86400)."' WHERE id=".$item['id'];
				$result = $pdb->Execute($sql);
				$point->update("moderate", $the_memberid);
				flash("success");
			}
			break;
		case "edit":		
			if(!empty($company_id)) {
				$company->primaryKey = "member_id";
				$company->newCheckStatus($companyinfo['status']);
				$company_info = $company->getInfoById($company_id);
				setvar("CompanyInfo",$company_info);
			}
			setvar("Forms", $form->getAttributes());
			if(!empty($id)){
			    $trade_info = $trade->getInfoByCondition($id, " AND t.member_id=".$the_memberid);
			    if (empty($trade_info) || !$trade_info) {
			        flash('data_not_exists');
			    }
			    if(!empty($trade_info['formattribute_ids'])){
			    	setvar("Forms", $form->getAttributes(explode(",", $trade_info['formattribute_ids'])));
			    }
			    $trade_info['expire_date'] = df($trade_info['expire_time']);
			    //industry ids, 1 to n.
			    if (!empty($trade_info['picture'])) {
			    	$trade_info['image'] = pb_get_attachmenturl($trade_info['picture'], "../");
			    }
			    if (!empty($trade_info['country_id'])) {
			    	$trade_info['country'] = $countries[$trade_info['country_id']]['picture'];
			    }
			    if(isset($trade_info['OfferPrimImaccount'])) {
			    	$tmp_personalinfo['MemberQQ'] = $trade_info['OfferPrimImaccount'];
			    }
			    if(isset($trade_info['OfferPrimTelnumber'])) {
			    	$tmp_personalinfo['MemberTel'] = $trade_info['OfferPrimTelnumber'];
			    }
			    if(!empty($trade_info['tag_ids'])){
			        $tag->getTagsByIds($trade_info['tag_ids'], true);
			        $trade_info['tag'] = $tag->tag;
			    }
			}else{
				if(!empty($companyinfo)){
					$trade_info['industry_id'] = $companyinfo['industry_id'];
					$trade_info['area_id'] = $companyinfo['area_id'];
				}else{
					$trade_info['area_id'] = $memberinfo['area_id'];
				}
			}
			if (!empty($trade_info['country_id'])) {
				$trade_info['country'] = $countries[$trade_info['country_id']]['picture'];
			}else{
				$trade_info['country'] = "blank.gif";
			}
			$trade_types = $trade->GetArray("SELECT * FROM ".$tb_prefix."tradetypes");
			foreach ($trade_types as $key=>$val){
				if($val['parent_id']==0){
					$set_types[$val['id']] = $val;
					foreach ($trade_types as $key1=>$val1){
						if ($val1['parent_id']==$val['id']){
							$set_types[$val['id']]['child'][$val1['id']] = $val1;
						}
					}
				}
			}
			setvar("select_tradetypes", $set_types);
			if (!empty($_GET['typeid'])) {
				setvar("type_id", intval($_GET['typeid']));
			}else{
				setvar("type_id", $trade_info['type_id']);
			}
			$r1 = $industry->disSubOptions($trade_info['industry_id'], "industry_");
			$r2 = $area->disSubOptions($trade_info['area_id'], "area_");
			$trade_info = am($trade_info, $r1, $r2);
			setvar("item",$trade_info);
			$tpl_file = "offer_edit";
			vtemplate($tpl_file);
			exit;
			break;
		case "stat":
	    	$tpl_file = "tradestat";
	    	$amount = $trade->findAll("Trade.type_id AS TradeTypeId,COUNT(Trade.id) AS CountTrade",null, $conditions,"Trade.type_id",0,10,"Trade.type_id");
	    	foreach ($amount as $val) {
	    		$stats[$val['TradeTypeId']] = $val['CountTrade'];
	    	}
	    	setvar("UserTradeStat",$stats);
	    	setvar("ProductAmount",$product->findCount(null, $conditions,"Product.id"));			
			break;
		default:
			break;
	}  
	if($do=="pro2offer" && !empty($_GET['productid'])){
        $product = new products();
        $item = $product->read("*", $_GET['productid'], null, "member_id=".$the_memberid);
        $item['country'] = "blank.gif";
        $trade_types = $trade->GetArray("SELECT * FROM ".$tb_prefix."tradetypes");
        foreach ($trade_types as $key=>$val){
        	if($val['parent_id']==0){
        		$set_types[$val['id']] = $val;
        		foreach ($trade_types as $key1=>$val1){
        			if ($val1['parent_id']==$val['id']){
        				$set_types[$val['id']]['child'][$val1['id']] = $val1;
        			}
        		}
        	}
        }
        setvar("select_tradetypes", $set_types);
        if (!empty($item)) {
			unset($item['id']);
        	$item['type_id'] = 2;
        	$item['title'] = $item['name'];
        	if (isset($item['picture'])) {
        		$item['image'] = pb_get_attachmenturl($item['picture'], "../");
        	}
        	setvar("type_id", $item['type_id']);
        	setvar("item", $item);
        }
       	$tpl_file = "offer_edit";
       	vtemplate($tpl_file);
       	exit;
    }
    if ($do=="update" && !empty($id)) {
    	$vals = array();
    	$vals['modified'] = $time_stamp;
    	$conditions[]= "status='1'";
    	$pre_update_time = $pdb->GetOne("select modified from {$tb_prefix}trades where id=".$id." and member_id=".$the_memberid);
    	if ($pre_update_time>($time_stamp-$tMaxHours*3600)) {
    		flash("only_one_time_within_hours");
    	}
    	$result = $trade->save($vals, "update", $id, null, $conditions);
    	if (!$result) {
    		flash("action_failed");
    	}else{
    		flash("success");
    	}
    }
	if ($do=="refresh" && !empty($id)) {
    	$vals = array();
    	$pre_submittime = $pdb->GetOne("select submit_time from {$tb_prefix}trades where id=".$id." and member_id=".$the_memberid);
    	if ($pre_submittime>($time_stamp-$tMaxDay*86400)) {
    		flash("allow_refresh_day");
    	}
    	$vals['submit_time'] = $time_stamp;
    	$vals['expire_days'] = 1;
    	$vals['expire_time'] = $time_stamp+(24*3600*$vals['expire_days']);
    	$conditions[]= "status='1'";
    	$result = $trade->save($vals, "update", $id, null, $conditions);
    	if (!$result) {
    		flash("action_failed");
    	}else{
    		flash("success");
    	}
    }
}
if (isset($_POST['do']) && !empty($_POST['data']['trade'])) {
	pb_submit_check('data');
	if(!empty($_POST['data']['multi'])) $_POST['data']['trade']['title'] = pb_lang_merge($_POST['data']['multi']);
	$_POST['data']['trade']['content'] = pb_lang_merge($_POST['data']['multita']);
    $res = $_POST['data']['trade'];
    $now_offer_amount = $trade->findCount(null, "created>".$today_start." AND member_id=".$the_memberid);
    if(isset($_POST['id'])){
    	$id = intval($_POST['id']);
    }
    if ($g['offer_check']) {
        $res['status'] = 0;
        $msg = 'msg_wait_check';
    }else {
        $res['status'] = 1;
        $msg = 'success';
    }
    if (!empty($_FILES['pic']['name'])) {
    	$attach_id = (empty($id))?"offer-".$the_memberid."-".($trade->getMaxId()+1):"offer-".$the_memberid."-".$id;
    	$attachment->rename_file = $attach_id;
		$attachment->upload_process();
        $res['picture'] = $attachment->file_full_url;
    }
	$res['tag_ids'] = $tag->setTagId($_POST['data']['tag']);
    $form_type_id = 1;
    if (!empty($company_id)) {
    	$res['company_id'] = $company_id;
    }else{
    	$res['company_id'] = 0;
    }
    if (!empty($_POST['data']['prim_im'])) {
    	if (!empty($_POST['data']['prim_imaccount'])) {
    		$im_arr = array();
    		switch ($_POST['data']['prim_im']) {
    			case 1:
    				$im_arr['qq'] = $_POST['data']['prim_imaccount'];
    				break;
    			case 2:
    				$im_arr['icq'] = $_POST['data']['prim_imaccount'];
    				break;
    			case 3:
    				$im_arr['msn'] = $_POST['data']['prim_imaccount'];
    				break;
    			case 4:
    				$im_arr['yahoo'] = $_POST['data']['prim_imaccount'];
    				break;
    			case 5:
    				$im_arr['skype'] = $_POST['data']['prim_imaccount'];
    				break;
    			default:
    				break;
    		}
    		$res['cache_contacts'] = serialize($im_arr);
    	}else{
    		$res['cache_contacts'] = serialize(array('qq'=>$memberinfo['qq'], 'icq'=>$memberinfo['icq'], 'skype'=>$memberinfo['skype'], 'yahoo'=>$memberinfo['yahoo'], 'msn'=>$memberinfo['msn']));
    	}
    }
    $tradefield_res['prim_tel'] = $_POST['data']['prim_tel'];
    $tradefield_res['prim_telnumber'] = $_POST['data']['prim_telnumber'];
    $tradefield_res['prim_im'] = $_POST['data']['prim_im'];
    $tradefield_res['prim_imaccount'] = $_POST['data']['prim_imaccount'];
    $res['industry_id'] = PbController::getMultiId($_POST['industry']['id']);
    $res['area_id'] = PbController::getMultiId($_POST['area']['id']);
    $res['highlight'] = 0;
    if (!empty($id)) {
		$item_ids = $form->Add($id,$_POST['data']['formitem']);
		$res['formattribute_ids'] = $item_ids;
    	$tradefield_res['trade_id'] = $id;
        $res['modified'] = $time_stamp;
        unset($res['member_id'], $res['company_id']);
        $res = $trade->save($res, "update", $id, null, $conditions);
    }else {
    	if ($g['max_offer'] && $now_offer_amount>=$g['max_offer']) {
    		flash('one_day_max');
    	}
        $res['member_id'] = $the_memberid;
        $res['company_id'] = $company_id;
        $res['submit_time'] = $res['created'] = $res['modified'] = $time_stamp;
        if (!empty($_POST['expire_days'])) {
        	if (array_key_exists($_POST['expire_days'], $trade_controller->getOfferExpires())) {
        		$res['expire_days'] = $_POST['expire_days'];
        		$res['expire_time'] = $res['expire_days']*86400+$time_stamp;
        	}
        }else{
        	$res['expire_days'] = 10;
        	$res['expire_time'] = $res['expire_days']*86400+$time_stamp;
        }
        if(isset($companyinfo['name'])) {
        	$res['cache_companyname'] = $companyinfo['name'];
        }
        $res = $trade->save($res);
        $new_id = $trade->table_name."_id";
        $new_id = $trade->$new_id;;
        $last_trade_id = $trade->getMaxId();
        $item_ids = $form->Add($last_trade_id, $_POST['data']['formitem']);
        if($item_ids){
        	$pdb->Execute("UPDATE {$tb_prefix}trades SET formattribute_ids='{$item_ids}' WHERE id=".$last_trade_id);
        }
        $tradefield_res['trade_id'] = $last_trade_id;
    }
    $tradefield->replace($tradefield_res);
    if(!$res) {
        flash("action_failed");
    }else{
    	flash($msg?$msg:"success");
    }
}
if (isset($_POST['del']) && !empty($_POST['tradeid'])) {
	$tRes = $trade->del($_POST['tradeid'], "member_id = ".$the_memberid);
	if($tRes) $pdb->Execute("DELETE from {$tb_prefix}tradefields WHERE member_id={$the_memberid} AND trade_id IN (".implode(",",$_POST['tradeid']).")");
}
if(isset($_POST['refresh'])){
	if (!empty($_POST['refresh']) && !empty($_POST['tradeid'])) {
		$vals = array();
		$pre_submittime = $pdb->GetOne("select max(submit_time) from {$tb_prefix}trades where member_id=".$the_memberid);
		if ($pre_submittime>($time_stamp-$tMaxDay*86400)) {
			flash("allow_refresh_day");
		}
		$vals['submit_time'] = $time_stamp;
		$vals['expire_days'] = 10;
		$vals['expire_time'] = $time_stamp+(24*3600*$vals['expire_days']);
		$conditions[]= "status='1'";
		$ids = implode(",", $_POST['tradeid']);
		$conditions[]= "id in (".$ids.")";
		$condition = implode(" AND ", $conditions);
		$sql = "update ".$trade->getTable()." set submit_time=".$time_stamp.",expire_days=10,expire_time=".$vals['expire_time']." where ".$condition;
		$result = $pdb->Execute($sql);
		if ($result) {
			flash("success");
		}else{
			flash("action_failed");
		}
	}
}
$amount = 0;
$amount = $trade->findCount(null, $conditions);
$result = $trade->findAll("*", null, $conditions, "Trade.submit_time DESC", 0, $trade->displaypg);
if (!empty($result)) {
	for($i=0; $i<count($result); $i++){
		$result[$i]['expire_date'] = df($result[$i]['expire_time']);
	}
	setvar("Items", $result);
}
setvar("OFFER_MODERATE_POINT", $G['setting']['offer_moderate_point']);
setvar("CheckStatus", $G['typeoption']["check_status"]);
setvar("Amount", $amount);
setvar("TimeStamp", $time_stamp);
vtemplate($tpl_file);
?>