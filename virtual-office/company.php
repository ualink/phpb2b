<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2154 $
 */
require("../libraries/common.inc.php");
require("room.share.php");
require(LIB_PATH .'time.class.php');
require(CLASS_PATH. 'validation.class.php');
$validate = new Validation();
check_permission("company");
uses("industry","area", "attachment", "companyfield", "typeoption");
$attachment = new Attachment('pic');
$area = new Areas();
$industry = new Industries();
$companyfield = new Companyfields();
$typeoption = new Typeoption();
$tpl_file = "company";
if (isset($_POST['do']) && !empty($_POST['data']['company'])) {
	pb_submit_check('data');
	$vals = $_POST['data']['company'];
	$company->doValidation($vals);
	if (!empty($company->validationErrors)) {
		setvar("item", $vals);
		setvar("Errors", $validate->show($company));
		vtemplate($tpl_file, true);
	}
	if (isset($companyinfo)) {
		if (empty($companyinfo['name'])) {
			$i18n = new L10n();
			$space_name = $i18n->translateSpaceName($_POST['data']['company']['name']);
			$space_name = str_replace(' ', '', $space_name);
			$vals['cache_spacename'] = $space_name;
			$vals['first_letter'] = substr($space_name, 0, 1);
			$member->updateSpaceName(array("id"=>$the_memberid), $space_name);
			if(isset($companyinfo['status']) && $companyinfo['status'] == 0){
				$vals['name'] = strip_tags($_POST['data']['company']['name']);
				$vals['english_name'] = strip_tags($vals['english_name']);
			}
		}
	}
	$vals['employee_amount'] = $vals['employee_amount'];
	if(!empty($vals['found_date'])) {
		$vals['found_date'] = Times::dateConvert($vals['found_date']);
	}
	if(!empty($_POST['manage_type']))
	{
		$managetype = implode(",",$vals['manage_type']);
		$vals['manage_type'] = $managetype;
	}
	$vals['property'] = $vals['property'];
	$vals['main_prod'] = strip_tags($vals['main_prod']);
	$vals['address'] = strip_tags($vals['address']);
	$vals['description'] = strip_tags(trim($vals['description']));
	$vals['boss_name'] = $vals['boss_name'];
	$vals['reg_address'] = $vals['reg_address'];
	$vals['reg_fund'] = $vals['reg_fund'];
	$vals['bank_from'] = $vals['bank_from'];
	$vals['bank_account'] = $vals['bank_account'];
	$vals['main_brand'] = $vals['main_brand'];
	$vals['year_annual'] = $vals['year_annual'];
	$vals['main_customer'] = $vals['main_customer'];
	$vals['main_biz_place'] = $vals['main_biz_place'];
	$vals['link_man'] = $vals['link_man'];
	$vals['position'] = $vals['position'];
	/**tel and fax**/
	$vals['tel'] = $company->getPhone($_POST['data']['telcode'], $_POST['data']['telzone'], $_POST['data']['tel']);
	$vals['fax'] = $company->getPhone($_POST['data']['faxcode'], $_POST['data']['faxzone'], $_POST['data']['fax']);
	$vals['mobile'] = $vals['mobile'];
	$vals['site_url'] = $vals['site_url'];
	$vals['email'] = $vals['email'];
	if (!preg_match("/^(http|ftp):/", $vals['site_url'])) {
		$vals['site_url'] = 'http://'.$vals['site_url'];
	}
	if(!empty($vals['main_market'])) {
		$mainmarket = implode(",",$vals['main_market']);
		$vals['main_market'] = $mainmarket;
	}
	if (!empty($_FILES['pic']['name'])) {
		$attachment->if_watermark = false;
		$attachment->if_thumb_middle = false;
		$attachment->rename_file = "company-".$time_stamp;
		$attachment->upload_process();
		$vals['picture'] = $attachment->file_full_url;
	}
	if ($g['company_check']) {
		$vals['status'] = 0;
		$msg = "wait_for_check";
	}else{
		$vals['status'] = 1;
	}
    $vals['industry_id'] = PbController::getMultiId($_POST['industry']['id']);
    $vals['area_id'] = PbController::getMultiId($_POST['area']['id']);
	if(!empty($company_id)){
		$vals['modified'] = $time_stamp;
		$vals['cache_membergroupid'] = $memberinfo['membergroup_id'];
		$company->save($vals, "update", $company_id, null, "member_id=".$the_memberid);
		$company->updateCachename($company_id, $vals['name']);
	} else {
		$vals['member_id'] = $the_memberid;
		$vals['cache_membergroupid'] = $memberinfo['membergroup_id'];
		$vals['created'] = $vals['modified'] = $time_stamp;
		$member->updateSpaceName($memberinfo['id'], $vals['english_name']);
		$company->save($vals);
		$key = $company->table_name."_id";
		$company_id = $company->$key;
	}
	$cfield_exits = $pdb->GetOne("SELECT company_id FROM {$tb_prefix}companyfields WHERE company_id={$company_id}");
	$companyfield->primaryKey = "company_id";
	if ($cfield_exits) {
		$companyfield->save($_POST['data']['companyfield'], "update", $company_id);
	}else{
		$_POST['data']['companyfield']['company_id'] = $company_id;
		$companyfield->save($_POST['data']['companyfield']);
	}
	$member->clearCache($the_memberid);
	$member->updateMemberCaches($the_memberid);
	flash($msg?$msg:"success");
}
setvar("MainMarkets", $G['typeoption']["main_market"]);
if(!empty($companyinfo['name'])){
	list(,$companyinfo['telcode'], $companyinfo['telzone'], $companyinfo['tel']) = $company->splitPhone($companyinfo['tel']);
	list(,$companyinfo['faxcode'], $companyinfo['faxzone'], $companyinfo['fax']) = $company->splitPhone($companyinfo['fax']);
	$companyinfo["option_manage_type"] = $typeoption->get_cache_key_unique("manage_type", $companyinfo['manage_type']);
	$selected['markets'] = explode(",",$companyinfo['main_market']);
	setvar("SelectedMarket",$selected['markets']);
	$companyinfo["option_reg_fund"] = $typeoption->get_cache_key_unique("reg_fund", $companyinfo['reg_fund']);
	$companyinfo["option_year_annual"] = $typeoption->get_cache_key_unique("year_annual", $companyinfo['year_annual']);
	$companyinfo["option_position"] = $typeoption->get_cache_key_unique("position", $companyinfo['position']);
	$companyinfo["option_employee_amount"] = $typeoption->get_cache_key_unique("employee_amount", $companyinfo['employee_amount']);
	$companyinfo["option_economic_type"] = $typeoption->get_cache_key_unique("economic_type", $companyinfo['property']);
	if(!empty($companyinfo["picture"])) {
		$companyinfo["logo"] = pb_get_attachmenturl($companyinfo["picture"], "../");
	}
	$company_fields = $pdb->GetRow("SELECT * FROM {$tb_prefix}companyfields WHERE company_id={$company_id}");
	if (!empty($company_fields)) {
		$companyinfo = am($companyinfo, $company_fields);
	}
	$companyinfo['found_year'] = date("Y", (int)$companyinfo['found_date']);
	$r1 = $industry->disSubOptions($companyinfo['industry_id'], "industry_");
	$r2 = $area->disSubOptions($companyinfo['area_id'], "area_");
	$companyinfo = am($companyinfo, $r1, $r2);
	setvar("item",$companyinfo);
	unset($selected,$companyinfo);
}
vtemplate($tpl_file);
?>