<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2223 $
 */
require("../libraries/common.inc.php");
require("room.share.php");
uses("product","producttype","form","attachment","tag","brand","productcategory","area","industry");
require(PHPB2B_ROOT.'libraries/page.class.php');
require(CACHE_COMMON_PATH."cache_type.php");
$G['membergroup'] = cache_read("membergroup");
check_permission("product");
$area = new Areas();
$industry = new Industries();
$productcategory = new Productcategories();
$page = new Pages();
$brand = new Brands();
$tag = new Tags();
$form = new Forms();
$product = new Products();
$producttype = new Producttypes();
$attachment = new Attachment('pic');
$conditions[] = "member_id = ".$the_memberid;
setvar("Countries", $countries = cache_read("country"));
setvar("ProductSorts", $_PB_CACHE['productsort']);
setvar("ProductTypes",$producttype->findAll('id,name', null, $conditions, "id DESC"));
setvar("Productcategories", $productcategory->getTypeOptions());
$tpl_file = "product";
if (!$company->Validate($companyinfo)) {
	flash("pls_complete_company_info", "company.php", 0);
}
if (isset($_POST['save'])) {
	$company->newCheckStatus($companyinfo['status']);
	if(!empty($_POST['data']['product'])){
		$product->setParams();
		$now_product_amount = $product->findCount(null, "created>".$today_start." AND member_id=".$the_memberid);
		$check_product_update = $g['product_check'];
		if ($check_product_update == 0) {
			$product->params['data']['product']['status'] = 1;
		}else {
			$product->params['data']['product']['status'] = 0;
			$message_info = 'msg_wait_check';
		}
		if(isset($_POST['id'])){
			$id = intval($_POST['id']);
		}
    	if (!empty($_FILES['pic']['name'])) {
    		$attach_id = (empty($id))?"product-".$the_memberid."-".($product->getMaxId()+1):"product-".$the_memberid."-".$id;
    		$attachment->rename_file = $attach_id;
			$attachment->upload_process();    		
    	    $product->params['data']['product']['picture'] = $attachment->file_full_url;
    	}
    	$form_type_id = 2;
    	$form_id = 1;
		$product->params['data']['product']['tag_ids'] = $tag->setTagId($_POST['data']['tag']);
		$product->params['data']['product']['cache_companyname'] = $companyinfo['name'];
		$product->params['data']['product']['industry_id'] = PbController::getMultiId($_POST['industry']['id']);
		$product->params['data']['product']['area_id'] = PbController::getMultiId($_POST['area']['id']);
		if (!empty($id)) {
			$item_ids = $form->Add($id,$_POST['data']['formitem'], $form_id, $form_type_id);
			$product->params['data']['product']['modified'] = $time_stamp;
			$product->params['data']['product']['formattribute_ids'] = $item_ids;
			$result = $product->save($product->params['data']['product'], "update", $id, null, $conditions);
		}else {
			if ($g['max_product'] && $now_product_amount>=$g['max_product']) {
				flash('one_day_max');
			}
			$product->params['data']['product']['member_id'] = $the_memberid;
			$product->params['data']['product']['company_id'] = $company_id;
			$product->params['data']['product']['created'] = $product->params['data']['product']['modified'] = $time_stamp;
			$result = $product->save($product->params['data']['product']);
			$new_id = $product->table_name."_id";
			$product_id = $product->$new_id;
			$item_ids = $form->Add($product_id, $_POST['data']['formitem'], $form_id, $form_type_id);
			if($item_ids){
				$pdb->Execute("UPDATE {$tb_prefix}products SET formattribute_ids='{$item_ids}' WHERE id=".$product_id);
			}
		}
		if ($result) {
			flash($message_info?$message_info:"success");
		}else {
			flash();
		}
	}
}
if (isset($_GET['do']) || isset($_GET['act'])) {
	$do = trim($_GET['do']);
	$action = null;
	if(isset($_GET['action'])) $action = trim($_GET['action']);
	if (isset($_GET['id'])) {
		$id = intval($_GET['id']);
	}
	if ($do == "edit") {
		if(!empty($company_id)) {
			$company->primaryKey = "member_id";
			$company->checkStatus($company_id);
			$company_info = $company->getInfoById($company_id);
			setvar("CompanyInfo",$company_info);
		}
		$sql = "SELECT id,name FROM {$tb_prefix}brands WHERE member_id=".$the_memberid;
		$user_brands = $pdb->GetArray($sql);
		$tmp_arr = array();
		if (!empty($user_brands)) {
			foreach ($user_brands as $user_brand) {
				$tmp_arr[$user_brand['id']] = $user_brand['name'];
			}
			setvar("UserBrands", $tmp_arr);
		}
		setvar("Forms", $attrs = $form->getAttributes(0,2));
		if (!empty($id)) {
			$productinfo = $product->read("*", $id, null, $conditions);
			if (empty($productinfo)) {
				flash("action_failed");
			}else {
				if (!empty($productinfo['picture'])) {
					$productinfo['image'] = pb_get_attachmenturl($productinfo['picture'], '../');
				}		   
				if(!empty($productinfo['tag_ids'])){
					$tag->getTagsByIds($productinfo['tag_ids'], true);
					$productinfo['tag'] = $tag->tag;
				}
				$r1 = $industry->disSubOptions($productinfo['industry_id'], "industry_");
				$r2 = $area->disSubOptions($productinfo['area_id'], "area_");
				$productinfo = am($productinfo, $r1, $r2);
				setvar("Forms", $form->getAttributes(explode(",", $productinfo['formattribute_ids']),2));
			}
		}else{
			$productinfo['industry_id'] = $companyinfo['industry_id'];
			$productinfo['area_id'] = $companyinfo['area_id'];
		}
		if (!empty($productinfo['country_id'])) {
			$productinfo['country'] = $countries[$productinfo['country_id']]['picture'];
		}else{
			$productinfo['country'] = "blank.gif";
		}
		setvar("item",$productinfo);
		$tpl_file = "product_edit";
		vtemplate($tpl_file);
		exit;
	}
	if ($do == "price") {
		if($action == "edit"){
			$tpl_file = "product.price";
		}
		vtemplate($tpl_file);
		exit;
	}
	if ($do == "state") {
		switch ($_GET['type']) {
			case "up":
				$state = 1;
				break;
			case "down":
				$state = 0;
				break;
			default:
				$state = 0;
				break;
		}
		if (!empty($id)) {
			$vals['state'] = $state;
			$updated = $pdb->Execute("UPDATE {$tb_prefix}products SET state={$state} WHERE id={$id} AND member_id={$the_memberid}");
			if (!$updated) {
				flash();
			}
		}else{
			flash();
		}
	}
	if (($do == "del" || $_GET['act']=="del") && !empty($id)) {
		$res = $product->read("id",$id);
		if($res){
			if(!$product->del($_GET['id'], $conditions)){
				flash();
			}
		}else {
			flash("data_not_exists");;
		}
	}	
}
if (isset($_GET['typeid']) && !empty($_GET['typeid'])) {
	$conditions[] = "type_id = ".$_GET['typeid'];
}
$amount = $product->findCount(null, $conditions,"Product.id");
$page->setPagenav($amount);
$result = $product->findAll("sort_id,id,name,picture,content,created,status,state", null, $conditions, "Product.id DESC", $page->firstcount, $page->displaypg);
if ($result) {
	$i_count = count($result);
	for ($i=0; $i<$i_count; $i++) {
		$result[$i]['image'] = pb_get_attachmenturl($result[$i]['picture'], '../', 'small');
	}
}
setvar("Items",$result);
setvar("nlink",$page->nextpage_link);
setvar("plink", $page->previouspage_link);
setvar("CheckStatus", explode(",",L('product_status', 'tpl')));
uaAssign(array("pagenav"=>$page->getPagenav()));
vtemplate($tpl_file);
?>