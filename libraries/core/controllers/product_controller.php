<?php
class Product extends PbController {
	var $name = "Product";
	
	function __construct()
	{
		parent::__construct();
		$this->loadModel("product");
	}
	
	function index()
	{
		$data = array();
		$_PB_CACHE['industry'] = cache_read("industry");
		require(CACHE_COMMON_PATH."cache_type.php");
		$index_latest_industry_ids = 10;
		$ProductSorts = $_PB_CACHE['productsort'];
		$result = $this->product->dbstuff->GetArray($sql = "SELECT distinct industry_id AS iid FROM {$this->product->table_prefix}products WHERE status=1 ORDER BY id DESC LIMIT 0,{$index_latest_industry_ids}");
		if (!empty($result)) {
			foreach ($result as $key=>$val) {
				$data[$val['iid']]['id'] = $val['iid'];
				if(isset($_PB_CACHE['industry'][1][$val['iid']])) $data[$val['iid']]['name'] = $_PB_CACHE['industry'][1][$val['iid']];
				$tmp_result = $this->product->dbstuff->GetArray("SELECT id,name,picture,sort_id,industry_id FROM {$this->product->table_prefix}products WHERE status=1 AND industry_id=".$val['iid']." ORDER BY id DESC LIMIT 0,5");
				if (!empty($tmp_result)) {
					foreach ($tmp_result as $key1=>$val1) {
						$data[$val['iid']]['sub'][$val1['id']]['id'] = $val1['id'];
						$data[$val['iid']]['sub'][$val1['id']]['name'] = $val1['name'];
						if(!empty($val1['sort_id'])) $data[$val['iid']]['sub'][$val1['id']]['sort'] = $ProductSorts[$val1['sort_id']];
						$data[$val['iid']]['sub'][$val1['id']]['image'] = pb_get_attachmenturl($val1['picture'], '', 'small');
					}
				}
			}
			setvar("IndustryProducts", $data);
		}
		render("product/index");
	}
	
	function detail()
	{
		global $viewhelper;
		$this->loadModel("industry");
		using("company","member","form", "tag","area","meta");
		$company = new Companies();
		$area = new Areas();
		$meta = new Metas();
		$tag = new Tags();
		$member = new Members();
		$form = new Forms();
		$tmp_status = explode(",",L('product_status', 'tpl'));
		$viewhelper->setPosition(L("product_center", 'tpl'), 'index.php?do=product');
		$viewhelper->setTitle(L("product_center", 'tpl'));
		if (isset($_GET['title'])) {
			$title = trim($_GET['title']);
			$res = $this->product->findByName($title);
			$id = $res['id'];
		}
		if (isset($_GET['id'])) {
			$id = intval($_GET['id']);
		}
		$info = $this->product->getProductById($id);
		if(empty($info) || !$info){
			flash("data_not_exists", '', 0);
		}
		$form_vars = array();
		if (isset($info['formattribute_ids'])) {
			$form_vars = $form->getAttributes(explode(",", $info['formattribute_ids']),2);
		}
		setvar("ObjectParams", $form_vars);
		$info['tag'] = '';//Initialize tag value
		if (!empty($info['tag_ids'])) {
			$tag_res = $tag->getTagsByIds($info['tag_ids']);
			if (!empty($tag_res)) {
				$tags = null;
				foreach ($tag_res as $key=>$val){
					$tags.='<a href="'.$this->url(array("module"=>"tag", "do"=>"product", "q"=>$val)).'" target="_blank">'.$val.'</a>&nbsp;';
				}
				$info['tag'] = $tags;
				unset($tags, $tag_res, $tag);
			}
		}
		if ($info['state']!=1) {
			flash("unvalid_product", '', 0);
		}
		if($info['status']!=1){
			$tmp_key = intval($info['status']);
			flash("wait_apply", '', 0);
		}
		if (!empty($info['member_id'])) {
			$member_info = $member->getInfoById($info['member_id']);
			$info['space_name'] = $member_info['space_name'];
			setvar("MEMBER", $member_info);
		}
		if (!empty($info['company_id'])) {
			$company_info = $company->getInfoById($info['company_id']);
			if (!empty($company_info)) {
				$info['companyname'] = $company_info['name'];
				$info['link_people'] = $company_info['link_man'];
				$info['address'] = $company_info['address'];
				$info['zipcode'] = $company_info['zipcode'];
				$info['site_url'] = $company_info['site_url'];
				$info['tel'] = pb_hidestr($company_info['tel']);
				$info['fax'] = pb_hidestr($company_info['fax']);
				$company_info = pb_lang_split_recursive($company_info);
				setvar("COMPANY", $company_info);
			}
		}
		$meta_info = $meta->getSEOById($id, 'product', false);
		empty($meta_info['title'])?$viewhelper->setTitle($info['name'], $info['picture']):$viewhelper->setTitle($meta_info['title']);
		empty($meta_info['description'])?$viewhelper->setMetaDescription($info['content']):$viewhelper->setMetaDescription($meta_info['description']);
		if(isset($meta_info['keyword'])) $viewhelper->setMetaKeyword($meta_info['keyword']);
		$viewhelper->setPosition($info['name']);
		$info['industry_names'] = $this->industry->disSubNames($info['industry_id'], null, true, "product");
		$info['area_names'] = $area->disSubNames($info['area_id'], null, true, "product");
		$info['title'] = strip_tags($info['name']);
		//delete the pre num.2011.9.1
		$info['tel'] = preg_replace('/\((.+?)\)/i', '', $info['tel']);
		$info['fax'] = preg_replace('/\((.+?)\)/i', '', $info['fax']);
		$info = pb_lang_split_recursive($info);
		setvar("item", $info);
		$this->product->clicked($id);
		render("product/detail");
	}
	
	function inquery()
	{
		global $viewhelper, $pb_userinfo;
		using("member","message","typeoption");
		$typeoption = new Typeoptions();
		$member = new Members();
		$pms = new Messages();
		if (isset($_POST['id']) && !empty($_POST['do']) && !empty($_POST['title'])) {
			pb_submit_check('inquery');
			$vals['type'] = 'inquery';
			$vals['title'] = $_POST['title'];
			$vals['content'] = implode("<br />", $_POST['inquery']);
			$result = $pms->SendToUser($pb_userinfo['pb_username'], $this->product->dbstuff->GetOne("SELECT username FROM {$this->product->table_prefix}members WHERE id=".intval($_POST['to_member_id'])), $vals);
			if(!$result){
				flash("failed", '', 0);
			}else{
				flash("success", '', 0);
			}
		}
		$pid = intval($_GET['id']);
		$sql = "SELECT * FROM {$this->product->table_prefix}products WHERE id=".$pid;
		$res = $this->product->dbstuff->GetRow($sql);
		if (empty($res) || !$res) {
			flash('data_not_exists', 'product/', 0);
		}else {
			if (!empty($res['picture'])) {
				$res['imgsmall'] = "attachment/".$res['picture'].".small.jpg";
				$res['imgbig'] = "attachment/".$res['picture'];
				$res['image'] = "attachment/".$res['picture'].".small.jpg";
			}else{
				$res['image'] = pb_get_attachmenturl('', '', 'small');
			}
			setvar("ImTypes", cache_read("typeoption", "im_type"));
			setvar("TelTypes", cache_read("typeoption", "phone_type"));
			setvar("item", pb_lang_split_recursive($res));
		}
		$viewhelper->setTitle($res['name']);
		$member_info = $this->product->dbstuff->GetRow("SELECT mf.first_name,mf.last_name,m.email as MemberEmail FROM {$this->product->table_prefix}members m LEFT JOIN {$this->product->table_prefix}memberfields mf ON mf.member_id=m.id WHERE m.id=".$res['member_id']);
		setvar("CompanyUser",$member_info['first_name'].$member_info['last_name']);
		render("product/inquery");
	}
	
	function price()
	{
		
	}
	
	function compare()
	{
		
	}
	
	function lists()
	{
		global $pos, $viewhelper;
		$viewhelper->setPosition(L("product_center", 'tpl'), 'index.php?do=product');
		$viewhelper->setTitle(L("product_center", 'tpl'));
		setvar("module", "product");
		$this->product->initSearch();
		$result = $this->product->Search($pos, $this->displaypg);
		setvar("items", $result);
 		$this->view->assign("total_count", $this->product->amount);
		render("list.default");
	}
}
?>