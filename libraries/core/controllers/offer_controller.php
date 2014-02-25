<?php
class Offer extends PbController {
	var $name = "Offer";
	
	function __construct()
	{
		parent::__construct();
	}
	
	function index()
	{
		render("offer/index");
	}
	
	function detail()
	{
		global $viewhelper, $G, $pb_user;
		$positions = $titles = array();
		uses("trade","member","company","tradefield","form","industry","area","meta");
		$offer = new Tradefields();
		$area = new Areas();
		$meta = new Metas();
		$industry = new Industries();
		$company = new Companies();
		$trade = new Trade();
		$trade_model = new Trades();
		$member = new Members();
		//$typeoption = new Typeoption();
		$form = new Forms();
		setvar("Genders", cache_read("typeoption", 'gender'));
		setvar("PhoneTypes", cache_read("typeoption", 'phone_type'));
		$viewhelper->setTitle(L("offer", "tpl"));
		$viewhelper->setPosition(L("offer", "tpl"), "index.php?do=offer");
		if (isset($_GET['title'])) {
			$title = trim($_GET['title']);
			$res = $trade_model->findByTitle($title);
			$id = $res['id'];
		}
		if (isset($_GET['id'])) {
			$id = intval($_GET['id']);
		}
		if(!empty($id)){
			$trade->setInfoById($id);
			$info = $trade->info;
			if (empty($info['id'])) {
				flash("data_not_exists", '', 0);
			}
			$info['title_clear'] = $info['title'];
			$info['title'].=(($G['setting']['offer_expire_method']==1||$G['setting']['offer_expire_method']==3) && $info['expdate']<$offer->timestamp)?"[".L("has_expired", "tpl")."]":'';
			$info['title'].=(!empty($info['if_urgent']))?"[".L("urgent_buy", "tpl")."]":'';
			if ($info['expdate']<$offer->timestamp && $G['setting']['offer_expire_method']==2) {
				flash("has_been_expired", URL, 0, $info['title']);
			}
		}else{
			flash("data_not_exists", '', 0);
		}
		if ($info['status']!=1) {
			flash("under_checking", null, 0, $info['title']);
		}
		$trade_types = $trade->getTradeTypes();
		$viewhelper->setTitle($trade_types[$info['type_id']]);
		$viewhelper->setPosition($trade_types[$info['type_id']], "index.php?do=offer&action=lists&typeid=".$info['type_id']);
		$trade_model->clicked($id);
		if ($info['require_point']>0) {
			//check member points
			if (empty($pb_user)) {
				flash("please_login_first", URL."logging.php");
			}
			$point = $member->field("points", "id='".$pb_user['pb_userid']."'");
			if ($point<$info['require_point']) {
				flash("not_enough_points", URL, 0, $info['require_point']);
			}
		}
		$form_vars = array();
		if (isset($info['formattribute_ids'])) {
			$form_vars = $form->getAttributes(explode(",", $info['formattribute_ids']));
		}
		setvar("ObjectParams", $form_vars);
		$info['pubdate'] = df($info['pubdate']);
		$info['expdate'] = df($info['expdate']);
		$info['image'] = pb_get_attachmenturl($info['picture']);
		$login_check = 1;
		if ($info['type_id']==1) {
			$login_check = $G['setting']['buy_logincheck'];
		}elseif ($info['type_id']==2){
			$login_check = $G['setting']['sell_logincheck'];
		}
		if (!empty($info['member_id'])) {
			$member_info = $member->getInfoById($info['member_id']);
			$info['link_people'] = $member_info['last_name'];
			$info['space_name'] = $member_info['space_name'];
			$info['tel'] = $member_info['tel'];
			$info['address'] = $member_info['address'];
			$info['zipcode'] = $member_info['zipcode'];
			$info['fax'] = $member_info['fax'];
			$info['site_url'] = $member_info['site_url'];
			setvar("MEMBER", $member_info);
		}
		if (!empty($info['company_id'])) {
			$company_info = $company->getInfoById($info['company_id']);
			if (!empty($company_info)) {
				$info['companyname'] = $company_info['name'];
				$info['link_people'] = $company_info['link_man'];
				$info['address'] = $company_info['address'];
				$info['zipcode'] = $company_info['zipcode'];
				$info['site_url'] = pb_hidestr($company_info['site_url']);
				$info['tel'] = pb_hidestr($company_info['tel']);
				$info['fax'] = pb_hidestr($company_info['fax']);
			}
			setvar("COMPANY", $company_info);
		}
		setvar("LoginCheck", $login_check);
		$info['title'] = strip_tags($info['title']);
		$info['industry_names'] = $industry->disSubNames($info['industry_id'], null, true, "offer");
		$info['area_names'] = $area->disSubNames($info['area_id'], null, true, "offer");
		//delete the pre num.2011.9.1
//		$info['tel'] = preg_replace('/\((.+?)\)/i', '', pb_hidestr($info['tel']));
//		$info['fax'] = preg_replace('/\((.+?)\)/i', '', pb_hidestr($info['fax']));
		$info = pb_lang_split_recursive($info);
		setvar("item",$info);
		$meta_info = $meta->getSEOById($id, 'trade', false);
		empty($meta_info['title'])?$viewhelper->setTitle($info['title'], $info['picture']):$viewhelper->setTitle($meta_info['title']);
		empty($meta_info['description'])?$viewhelper->setMetaDescription($info['content']):$viewhelper->setMetaDescription($meta_info['description']);
		$viewhelper->setPosition($info['title_clear']);
		if (isset($meta_info['keyword'])) {
			$viewhelper->setMetaKeyword($meta_info['keyword']);
		}
		setvar("forward", $this->url(array("module"=>"offer", "id"=>$id)));
		render("offer/detail");
	}
	
	function post()
	{
		global $G, $viewhelper;
		require(CLASS_PATH. "validation.class.php");
		$validate = new Validation();
		if (session_id() == '' ) { 
			require_once(LIB_PATH. "session_php.class.php");
			$session = new PbSessions();
		}
		uses("trade","member","tradefield","tag");
		$tag = new Tags();
		$offer = new Tradefields();
		$member = new Members();
		$trade = new Trades();
		$expires = cache_read("typeoption", "offer_expire");
		setvar("Genders", cache_read("typeoption", "gender", 1, array("0", "-1")));
		setvar("PhoneTypes", cache_read("typeoption", "phone_type"));
		setvar("ImTypes", cache_read("typeoption", "im_type"));
		$if_visit_post = $G['setting']['vis_post'];
		if(!$if_visit_post){
			$this->view->flash('visitor_forbid', URL, 0);
		}
		//for temp upgrade.
		if (!file_exists(CACHE_LANG_PATH. "locale.js")) {
			require(LIB_PATH. "cache.class.php");
			$cache = new Caches();
			$cache->updateLanguages();
			$cache->writeCache("javascript", "javascript");
		}
		$trade_types = $trade->GetArray("SELECT * FROM ".$trade->table_prefix."tradetypes");
		foreach ($trade_types as $key=>$val){
			if($val['parent_id']==0){
				$set_types[$val['id']] = pb_lang_split_recursive($val);
				foreach ($trade_types as $key1=>$val1){
					if ($val1['parent_id']==$val['id']){
						$set_types[$val['id']]['child'][$val1['id']] = pb_lang_split_recursive($val1);
					}
				}
			}
		}
		if (isset($_GET['typeid'])) {
			setvar("type_id", intval($_GET['typeid']));
		}
		if (isset($_GET['industryid'])) {
			setvar("industry_id", intval($_GET['industryid']));
		}
		if (isset($_GET['areaid'])) {
			setvar("area_id", intval($_GET['areaid']));
		}
		setvar("select_tradetypes", $set_types);
		$viewhelper->setPosition(L("free_release_offer", "tpl"));
		setvar("OfferExpires", $expires);
		setvar("sid",md5(uniqid($offer->timestamp)));
		capt_check("capt_post_free");
		render("offer/post");		
	}
	
	function add()
	{
		global $G;
		require(CLASS_PATH. "validation.class.php");
		$validate = new Validation();
		uses("trade","member","tradefield","tag");
		$tag = new Tags();
		$offer = $tradefield = new Tradefields();
		$member = new Members();
		$trade = new Trades();
		if (isset($_POST['visit_post'])) {
			capt_check("capt_post_free");
			pb_submit_check('visit_post');
			$_POST['data']['trade']['title'] = pb_lang_merge($_POST['data']['multi']);
			$trade->setParams();
			$tradefield->setParams();
			$if_title_exists = $trade->findByTitle($trade->params['data']['trade']['title']);
			if (!empty($if_title_exists)) {
				$trade->validationErrors[] = L("semilar_offer_post");
			}
			if (!$validate->notEmpty($trade->params['data']['trade']['title'])) {
				$trade->validationErrors[] = L("title_cant_be_empty");
			}
			$trade->params['expire_days'] = $_POST['expire_days'];
			$if_check = $G['setting']['vis_post_check'];
			$msg = null;
			$words = $trade->dbstuff->GetArray("SELECT * FROM {$trade->table_prefix}words");
			if (!empty($words)) {
				foreach ($words as $word_val) {
					if(!empty($word_val['title'])){
						str_replace($word_val['title'], "***", $trade->params['data']['trade']['title']);
						str_replace($word_val['title'], "***", $trade->params['data']['trade']['content']);
					}
				}
				$item['forbid_word'] = implode("\r\n", $tmp_str);
			}
			if ($if_check) {
				$trade->params['data']['trade']['status'] = 0;
				$msg = 'pls_wait_for_check';
			}else{
				$trade->params['data']['trade']['status'] = 1;
				$msg = 'success';
			}
			if (!empty($trade->validationErrors)) {
				setvar("item", am($trade->params['data']['trade'], $tradefield->params['data']['tradefield']));
				setvar("Errors", $validate->show($trade));
				render("offer/post");		
			}else{
				$trade->params['data']['trade']['industry_id'] = implode(",",$_POST['industry']['id']);
				$trade->params['data']['trade']['area_id'] = implode(",", $_POST['area']['id']);
				$result = $trade->Add();
				if ($result) {
					flash($msg);
				}else{
					flash();
				}
			}
		}
	}
	
	function buy()
	{
		
	}
	
	function sell()
	{
		
	}
	
	/**
	 * search
	 * @list
	 */
	function lists()
	{
		global $G, $viewhelper, $pos;
		uses("trade","industry","area","tradefield","form","tag");
		$trusttypes = cache_read("trusttype");
		$countries = cache_read("country");
		$membergroups = cache_read("membergroup");
		$area = new Areas();
		$offer = new Tradefields();
		$trade = new Trades();
		$form = new Forms();
		$industry = new Industries();
		$tag = new Tags();
		$conditions = array();
		$industry_id = $area_id = 0;
		$conditions[]= "t.status=1";
		!empty($_GET) && $_GET = clear_html($_GET);
		if (isset($_GET['navid'])) {
			setvar("nav_id", intval($_GET['navid']));
		}
		$viewhelper->setTitle(L('offer', 'tpl'));
		$viewhelper->setPosition(L('offer', 'tpl'), "index.php?do=offer");
		$trade_types = cache_read("type", "offertype");
		if (isset($_GET['typeid'])) {
			$type_id = intval($_GET['typeid']);
			$conditions[]= "t.type_id='".$type_id."'";
			setvar("typeid", $type_id);
			$type_name = $trade_types[$type_id];
			$viewhelper->setTitle($type_name);
			$viewhelper->setPosition($type_name, "index.php?do=offer&action=lists&typeid=".$type_id);
		}
		if (isset($_GET['industryid'])) {
			$industry_id = intval($_GET['industryid']);
			$tmp_info = $industry->setInfo($industry_id);
			if (!empty($tmp_info)) {
				$sub_ids = $industry->getSubDatas($tmp_info['id']);
				$sub_ids = array_keys($sub_ids);
				$conditions[] = "t.industry_id IN (".implode(",", $sub_ids).")";
				$viewhelper->setTitle($tmp_info['name']);
				$viewhelper->setPosition($tmp_info['name'], "index.php?do=offer&action=lists&industryid=".$tmp_info['id']);
			}
		}
		if (isset($_GET['areaid'])) {
			$area_id = intval($_GET['areaid']);
			$tmp_info = $area->setInfo($area_id);
			if (!empty($tmp_info)) {
				$sub_ids = $area->getSubDatas($tmp_info['id']);
				$sub_ids = array_keys($sub_ids);
				$conditions[] = "t.area_id IN (".implode(",", $sub_ids).")";
				$viewhelper->setTitle($tmp_info['name']);
				$viewhelper->setPosition($tmp_info['name'], "index.php?do=offer&action=lists&areaid=".$tmp_info['id']);
			}
		}
		if (isset($_GET['type'])) {
			if($_GET['type']=="urgent"){
				$conditions[]="t.if_urgent='1'";
			}
		}
		if (!empty($_GET['price_start']) || !empty($_GET['price_end'])) {
			$conditions[] = "t.price BETWEEN ".intval($_GET['price_start'])." AND ".intval($_GET['price_end']);
		}
		if (!empty($_GET['picture'])) {
			$conditions[] = "t.picture!=''";
		}
		if (!empty($_GET['urgent'])) {
			$conditions[] = "t.if_urgent=1";
		}
		if (!empty($_GET['commend'])) {
			$conditions[] = "t.if_commend=1";
		}
		if (!empty($_GET['country'])) {
			$conditions[] = "t.country_id='".intval($_GET['country'])."'";
		}
		if (!empty($_GET['sure'])) {
			$conditions[] = "m.trusttype_ids='".intval($_GET['sure'])."'";
		}
		if (!empty($_GET['date'])) {
			$d = intval($_GET['date']);
			if ($d<=7948800) {
				$conditions[] = "t.submit_time<='".intval($_GET['date'])."'";
			}
		}
		if (isset($_GET['q'])) {
			$searchkeywords = $_GET['q'];
			$viewhelper->setTitle(L("search_in_keyword", "tpl", $searchkeywords));
			$viewhelper->setPosition(L("search_in_keyword", "tpl", $searchkeywords));
			$conditions[]= "t.title like '%".$searchkeywords."%'";
			setvar("highlight_str", $searchkeywords);
		}
		if (isset($_GET['pubdate'])) {
			switch ($_GET['pubdate']) {
				case "l3":
					$conditions[] = "t.submit_time>".($offer->timestamp-3*86400);
					break;
				case "l10":
					$conditions[] = "t.submit_time>".($offer->timestamp-10*86400);
					break;
				case "l30":
					$conditions[] = "t.submit_time>".($offer->timestamp-30*86400);
					break;
				default:
					break;
			}
		}
		if ($G['setting']['offer_expire_method']==2 || $G['setting']['offer_expire_method']==3) {
			$conditions[] = "t.expire_time>".$offer->timestamp;
		}
		$amount = $trade->findCount(null, $conditions, null, "t");
		$result = $trade->getRenderDatas($conditions, $G['setting']['offer_filter']);
		$important_result = $trade->getStickyDatas();
		setvar("StickyItems", $important_result);
		setvar('items', $result);
		setvar('trusttype', $trusttypes);
		setvar('countries', $countries);
		setvar("paging", array('total'=>$amount));
		render("offer/list");
	}
	
	function wholesale()
	{
		render("offer/".__FUNCTION__, true);
	}
	
	function invest()
	{
		render("offer/".__FUNCTION__, true);
	}
}
?>