<?php
class Space extends PbController {
	public $name = "Space";
	public $menu = null;
	public $links;
	public $member_id;
	public $company_id;
	public $base_url;
	public $skin_dir;
	public $skin_path;
	public $user_id;
	public $module;
	public $spaceModules = array(
			"intro", 
			"home", 
			"index", 
			"product", 
			"offer", 
			"hr", 
			"job", 
			"news", 
			"album", 
			"contact", 
			"feedback"
			);
 	public static $instance = NULL;
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function init(){
		global $subdomain_support, $rewrite_able, $pdb, $time_stamp, $tb_prefix, $absolute_uri, $attachment_url, $G, $viewhelper;
		//espcial done for multi arrTemplate
		$this->loadModel("space");
		$sections = 'space';
		$this->view->configLoad('default.conf', $sections);
		if(isset($_GET['userid'])) {
			$this->userid = trim(htmlspecialchars($_GET['userid']));
		}
		if ($subdomain_support && $rewrite_able) {
			$hosts = explode($subdomain_support, pb_getenv('HTTP_HOST'));
			if(($hosts[0]!="www")){
//				$this->userid = trim($hosts[0]);
			}
		}
		$G['membergroup'] = cache_read("membergroup");
		using("member","company");
		$member = new Members();
		$company = new Companies();
		$space_name = '';
		if (empty($theme_name)) {
			$theme_name = "default";
			$style_name = (isset($G['setting']['theme']) && !empty($G['setting']['theme']))?$G['setting']['theme']:"default";
			$ADODB_CACHE_DIR = DATA_PATH.'dbcache';
		}
		$this->view->assign("theme_img_path", "templates/".$theme_name."/");
		$this->view->assign('ThemeName', $theme_name);
		$cache_data = $push_data = array();
		if (!empty($this->userid)) {
			$userid = $this->userid;
			$member->setInfoBySpaceName($this->userid);
			if(!empty($member->info['id'])) {
				$this->member_id = $member->info['id'];
				$company->setInfoByMemberId($member->info['id']);
			}else{
				$company->setInfoBySpaceName($this->userid);
			}
			$push_data['company'] = $company->info;
			$this->company_id = $company->info['id'];
			$push_data['member'] = $member->info;
		}elseif(!empty($_GET['id'])) {
			$id = intval($_GET['id']);
			$company->id = $this->company_id = $id;
			$company->setInfoById($id);
			if (!empty($company->info['member_id'])) {
				$member->id = $this->member_id = $company->info['member_id'];
			}
			$push_data['company'] = $company->info;
			$push_data['member'] = $member->info;
		}
		if (isset($company->info['status']) && $company->info['status']===0) {
			header_sent(L('company_checking'));
			exit;
		}elseif (empty($company->info) || !$company->info) {
			header_sent(L('data_not_exists'));
			exit;
		}
		$cache_data = $pdb->GetRow("SELECT data2 AS style FROM {$tb_prefix}spacecaches WHERE company_id='".$company->info['id']."'");
		if(isset($cache_data['style'])) $skin_extra_style = $cache_data['style'];
		if(!empty($company->info['created'])){
			$time_tmp = $time_stamp-$company->info['created'];
			$company->info['year_sep'] = $time_tmp = ceil($time_tmp/(3600*24*365));
		}
		if (empty($company->info['email'])) {
			$company->info['email'] = $G['setting']['service_email'];
		}
		if (empty($company->info['picture'])) {
			$company->info['logo'] = $absolute_uri.pb_get_attachmenturl('', '', 'big');
		}else{
			$company->info['logo'] = $absolute_uri.$attachment_url.$company->info['picture'];
		}
		$company->info = pb_lang_split_recursive($company->info);
		$company->info['description'] = nl2br(strip_tags($company->info['description']));
		$is_set_default_skins = false;
		$member_templet_id = $member->info['templet_id'];
		if (isset($_GET['force_templet_id'])) {
			$member_templet_id = intval($_GET['force_templet_id']);
		}
		if(!empty($member_templet_id)){
			$skin_path_info = $pdb->GetRow("SELECT name,directory FROM {$tb_prefix}templets WHERE type='user' AND status='1' AND id='".$member_templet_id."'");
		}
		if (empty($skin_path_info)) {
			$skin_path_info = $pdb->GetRow("SELECT name,directory FROM {$tb_prefix}templets WHERE type='user' AND is_default='1'");
			if (empty($skin_path_info)) {
				$is_set_default_skins = true;
			}
		}elseif (!is_dir(PHPB2B_ROOT.$skin_path_info)){
			$is_set_default_skins = true;
		}
		if ($is_set_default_skins) {
			$skin_path_info = array();
			$skin_path_info[] = "default";
			$skin_path_info[] = "templates/skins/default/";
		}
		list($skin_path, $skin_dir) = $skin_path_info;
		if (strpos($skin_dir, "templates")===false) {
			$skin_dir = "templates/".$skin_dir;//for 4.3 upgrade from 4.3 below,begin 2012.10
		}
		$this->skin_path = $skin_path;
		$this->skin_dir = $skin_dir;
		uaAssign(array(
		"SkinName"=>$skin_path,
		"ThemeName"=>$skin_path,
		"SkinPath"=>$skin_dir,
		"COMPANY"=>$company->info,
		"MEMBER"=>$member->info,
		));
		$this->view->setTemplateDir(PHPB2B_ROOT ."templates".DS."skins".DS);
		$this->view->setCompileDir(DATA_PATH."templates_c".DS.
		$this->lang.DS."skin".DS.$skin_path.DS);
		if(isset($member->info['id'])) $this->setLinks($member->info['id']);
		$this->setMenu($company->info['cache_spacename'], $this->spaceModules);
		$product_types = $pdb->GetArray("SELECT *,id as typeid,name as typename FROM {$tb_prefix}producttypes WHERE company_id=".$company->info['id']);//set and get db cache
		setvar("ProductTypes",$product_types);
		$group_info = array();
		$group_info['year'] = $time_tmp;
		if (!empty($member->info['membergroup_id']['name'])) {
			$group_info['name'] = $G['membergroup'][$member->info['membergroup_id']]['name'];
		}else{
			$group_info['name'] = L("undefined_image", "tpl");
		}
		if (!empty($member->info['membergroup_id']['avatar'])) {
			$group_info['image'] = $absolute_uri.STATICURL."images/group/".$G['membergroup'][$member->info['membergroup_id']]['avatar'];
		}else{
			$group_info['image'] = $absolute_uri.STATICURL. "images/group/formal.gif";
		}
		setvar("GROUP", $group_info);
		//for old version
		if(isset($member->info['membergroup_id']['name'])) setvar("GroupName", $G['membergroup'][$member->info['membergroup_id']]['name']);
		if(isset($member->info['membergroup_id']['avatar'])) setvar("GroupImage", $absolute_uri.STATICURL."images/group/".$G['membergroup'][$member->info['membergroup_id']]['avatar']);
		setvar("Menus", $this->getMenu());
		setvar("Links", $this->getLinks());
		$space_url = $this->rewrite($company->info['cache_spacename'], $company->info['id']);
		setvar("space_url", $space_url);
		setvar("SpaceUrl", $absolute_uri.$skin_dir);
		$space_extra_style = '';
		setvar("SpaceExtraStyle", $space_extra_style);
		if (!empty($skin_extra_style)) {
			$space_extra_style = $absolute_uri.$skin_dir."styles/".$skin_extra_style."/";
			setvar("SpaceExtraStyle", $space_extra_style);
		}
		setvar("BASEMAP", $absolute_uri.$skin_dir);
		$pdb->Execute("UPDATE {$tb_prefix}companies SET clicked=clicked+1 WHERE id='".$company->info['id']."'");
		if (!empty($arrTemplate)) {
			$this->view->assign($arrTemplate);
		}
		if (!empty($_GET['module'])) {
			$this->module = trim($_GET['module']);
		}
		$this->view->assign("cur", "space_".$this->module);
	}
	
	public function index()
	{
		$this->init();
		$this->render("index");
	}
	
	public function detail()
	{
		global $pdb, $tb_prefix;
		$this->init();
		$id = intval($_GET['id']);
		$info = $pdb->GetRow("SELECT * FROM {$tb_prefix}jobs WHERE id=".$id);
		setvar("detail_item", $info);
		$this->render($this->module."_detail");
	}
	
	public function lists()
	{
		$this->init();
		switch ($this->module) {
			case "album":
				if ($this->member_id) {
					$join = "LEFT JOIN {$this->table_prefix}attachments a ON a.id=Album.attachment_id";
					$sql = sprintf("SELECT a.title,a.description,Album.id,a.attachment as thumb FROM %salbums AS Album %s WHERE Album.member_id='%s' ORDER BY Album.id desc",$this->table_prefix, $join, $this->member_id);
					$result = $this->dbstuff->GetArray($sql);
				}
				if (!empty($result)) {
					$count = count($result);
					for($i=0; $i<$count; $i++){
						$result[$i]['image'] = URL. pb_get_attachmenturl($result[$i]['thumb'], '', 'small');
						$result[$i]['middleimage'] = URL. pb_get_attachmenturl($result[$i]['thumb']);
					}
					setvar("Items", $result);
				}
				break;
		
			default:
				if (empty($this->module)) {
					$this->module = "index";
				}
				
				break;
		}
		$this->render($this->module);
	}
	
	public function rewrite($userid, $id = 0, $do = null)
	{
		//get other params, such as do,action func_args
		global $subdomain_support, $topleveldomain_support, $rewrite_able;
		$userid = rawurlencode($userid);
		if (!empty($id)) {
			$url = URL."index.php?do=space&id=".$id;
		}else{
			$url = URL."index.php?do=space&action=lists&userid=".$userid."&module=".$do;
		}
		return $url;
	}
	
	public function setLinks($memberid, $companyid = 0)
	{
//		$this->init();
		$this->links = $this->space->getSpaceLinks($memberid, $companyid = 0);
	}
	
	public function getLinks()
	{
//		$this->init();
		return $this->links;
	}
	
	public function rewriteDetail($module, $id = 0)
	{
//		$this->init();
		global $rewrite_able;
		if ($rewrite_able) {
			switch ($module) {
				case "product":
					$url = URL.$module."/detail/".$id.".html";
					break;
				case "offer":
					$url = URL.$module."/detail/".$id.".html";
					break;
				default:
					$url = $this->base_url.$module."/detail-".$id.".html";
					break;
			}
		}else{
			switch ($module) {
				case "product":
					$url = URL."index.php?do=".$module."&action=detail&id=".$id;
					break;
				case "offer":
					$url = URL."index.php?do=".$module."&action=detail&id=".$id;
					break;
				default:
					$url = $this->base_url."&do={$module}&nid=".$id;
					break;
			}			
		}
		return $url;
	}
	
	public function rewriteList($module, $additionalParams = null)
	{
//		$this->init();
		global $rewrite_able;
		if ($rewrite_able) {
			return $this->base_url.$module."/list-0-1.html";
		}else{
			return $this->base_url;
		}
	}

	public function setMenu($user_id, $space_actions){
		global $subdomain_support, $rewrite_able;
		$tmp_menus = array();
		$user_id = rawurlencode($user_id);
		foreach ($space_actions as $key=>$val) {
			$tmp_menus[$val] = URL."index.php?do=space&action=lists&module=".$val."&userid=".$user_id;
		}
		$this->menu = $tmp_menus;
	}

	public function setBaseUrlByUserId($user_id, $space_actions){
//		$this->init();
		global $subdomain_support, $rewrite_able;
		$user_id = rawurlencode($user_id);
		if($subdomain_support && $rewrite_able){
			$this->base_url = "http://".$user_id.$subdomain_support."/space/";
		}elseif($rewrite_able){
			$this->base_url = URL."space/".$user_id."/";
		}else{
			$this->base_url = URL."space/?userid=".$user_id;
		}
		return $this->base_url;
	}

	public function getMenu(){
//		$this->init();
		return $this->menu;
	}
	
	//only for space, not for site & admin & office.
	public function render($tpl_file, $ext = ".html")
	{
//		$this->init();
		$tpl_path = $this->view->getTemplateDir(0);
		if(!file_exists($tpl_path.$this->skin_path.DS.$tpl_file.$ext)){
			$this->view->setTemplateDir($tpl_path."default".DS);
		}else{
			$this->view->setTemplateDir($tpl_path.$this->skin_path.DS);
		}
		$this->view->display("{$tpl_file}{$ext}");
	}
}
?>