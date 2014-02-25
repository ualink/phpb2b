<?php
class Companies extends PbModel {
 	var $name = "Company";
	var $configs = null;
	var $info = null;
	var $id;
 	var $validate = array(
	'name' => array('required' => true),
	'description' => array( 'required' => true),
	);

 	function __construct()
 	{
 		parent::__construct();
 		$this->validate['name']['message'] = L("please_input_companyname");
 		$this->validate['description']['message'] = L("please_input_companydesc");
 	}
 	
 	function Validate($data)
 	{
 		if (is_array($data)) {
 			foreach ($this->validate as $key=>$val) {
 				if (empty($data[$key])) {
 					return false;
 				}
 			}
 			return true;
 		}
 		return false;
 	}
 	
 	function initSearch()
 	{
 		if (isset($_GET['industryid'])) {
 			if (strpos($_GET['industryid'], ",")!==false) {
 				$this->condition[]= "Company.industry_id IN (".trim($_GET['industryid']).")";
 			}else{
	 			$industryid = intval($_GET['industryid']);
	 			$this->condition[]= "Company.industry_id='".$industryid."'";
 			}
 		}
 		if (isset($_GET['areaid'])) {
 			if (strpos($_GET['areaid'], ",")!==false) {
 				$this->condition[]= "Company.area_id IN (".trim($_GET['areaid']).")";
 			}else{
	 			$areaid = intval($_GET['areaid']);
	 			$this->condition[]= "Company.area_id='".$areaid."'";
 			}
 		}
 		if (isset($_GET['type'])) {
 			if ($_GET['type']=="commend") {
 				$this->condition[]="Company.if_commend='1'";
 			}
 		}
 		if(!empty($_GET['le'])){
 			$this->condition[]="Company.first_letter='".strtolower($_GET['le'])."'";
 		}
 		if(isset($_GET['typeid'])){
 			$this->condition[]="Company.type_id=".intval($_GET['typeid']);
 		}
 		if(isset($_GET['q'])) {
 			$searchwords = $_GET['q'];
 			$this->condition[]= "Company.name like '%".$searchwords."%'";
 		}
 		if (isset($_GET['main_prod'])) {
 			$this->condition[]= "Company.main_prod='".$_GET['main_prod']."'";
 		}
 		if (!empty($_GET['total_count'])) {
 			$this->amount = intval($_GET['total_count']);
 		}else{
 			$this->amount = $this->findCount();
 		}
 		if (!empty($_GET['orderby'])) {
 			switch ($_GET['orderby']) {
 				case "dateline":
 					$this->orderby = "created DESC";
 					break;
 				default:
 					break;
 			}
 		}
 	}
 	
 	function Search($firstcount, $displaypg)
 	{
 		global $cache_types, $G;
 		uses("space","industry","area");
 		$space = new Space();
 		$area = new Areas();
 		$industry = new Industries();
 		$cache_options = cache_read('typeoption');
 		$area_s = $space->array_multi2single($area->getCacheArea());
 		$industry_s = $space->array_multi2single($industry->getIndustry());
 		$result = $this->findAll("*,name AS title,description AS digest", null, null, $this->orderby, $firstcount, $displaypg);
		if (!isset($G['membergroup'])) {
			$G['membergroup'] = cache_read("membergroup");
		}
 		while(list($keys,$values) = each($result)){
 			$r = array();
 			$result[$keys]['pubdate'] = df($values['created']);
 			$result[$keys]['typename'] = $cache_options['manage_type'][$values['manage_type']];
 			$result[$keys]['thumb'] = $result[$keys]['logo'] = pb_get_attachmenturl($values['picture'], '', 'small');
 			$result[$keys]['gradeimg'] = URL.STATICURL.'images/group/'.
 			$G['membergroup'][$result[$keys]['cache_membergroupid']]['avatar'];
 			$result[$keys]['gradename'] = $G['membergroup'][$result[$keys]['cache_membergroupid']]['name'];
 			if (!empty($result[$keys]['area_id'])) {
 				$r[] = $area_s[$result[$keys]['area_id']];
 			}
 			if (!empty($result[$keys]['industry_id'])) {
 				$r[] = $industry_s[$result[$keys]['industry_id']];
 			}
 			$r[] = L("integrity_index", "tpl")."(".$result[$keys]['cache_credits'].")";
 			if (!empty($r)) {
 				$result[$keys]['extra'] = implode(" - ", $r);
 			}
 			$result[$keys]['url'] = $space->rewrite($values['member_id'], $values['id']);
 		}
 		return $result;
 	}
 	
 	function setInfo($info)
 	{
 		$this->info = $info;
 	}
 	
 	function getInfo()
 	{
 		return $this->info;
 	}
 	
 	function setInfoById($company_id)
 	{
 		$result = array();
 		$sql = "SELECT c.* FROM {$this->table_prefix}companies c WHERE c.id='{$company_id}'";
 		$result = $this->dbstuff->GetRow($sql);
 		$this->info = $result;
 	}
 	
 	function setInfoByMemberId($member_id)
 	{
 		$return = $field_info = array();
 		$sql = "SELECT c.*,cf.* FROM {$this->table_prefix}companies c  left join {$this->table_prefix}companyfields cf ON cf.company_id=c.id WHERE c.member_id='{$member_id}'";
 		$result = $this->dbstuff->GetRow($sql);
 		$this->info = array_merge($result, $field_info);
 	} 	
 	
 	function setInfoBySpaceName($user_id)
 	{
 		$return = $field_info = array();
 		$sql = "SELECT c.* FROM {$this->table_prefix}companies c LEFT JOIN {$this->table_prefix}companyfields cf ON cf.company_id=c.id WHERE c.cache_spacename='{$user_id}' OR c.name='{$user_id}'";
 		$result = $this->dbstuff->GetRow($sql);
 		if (empty($result) || !$result) {
 			return false;
 		}
 		$return = array_merge($result, $field_info);
 		$this->info = $result; 		
 	}
 	
 	function Delete($ids, $conditions = array())
	{
		$condition = array();
		if (is_array($ids)) {
			$condition[] = "id IN (".implode(",", $ids).")";
		}else{
			$condition[] = "id=".$ids;
		}
		$condition = am($condition, $conditions);
		$this->setCondition($condition);
		$this->dbstuff->Execute("DELETE FROM {$this->table_prefix}companies,{$this->table_prefix}companyfields USING {$this->table_prefix}companies LEFT JOIN {$this->table_prefix}companyfields ON {$this->table_prefix}companyfields.company_id={$this->table_prefix}companies.id ".$this->getCondition());
		return true;
	}

	function getCompanyInfo($companyid, $memberid = null)
	{
		$sql = "SELECT * FROM ".$this->getTable(true)." WHERE 1 ";
		if(!is_null($memberid)) $sql.=" AND member_id=".$memberid;
		if(!is_null($companyid)) $sql.=" AND id=".$companyid;
		$res = $this->dbstuff->GetRow($sql);
		return $res;
	}

	function statCompany()
	{
		$sql = "select type_id,count(Company.id) as Amount from ".$this->getTable(true)." group by Company.type_id";
		$return = $this->dbstuff->GetAll($sql);
		foreach ($return as $key=>$val) {
			$m[$val['type_id']] = $val['Amount'];
		}
		if($return) $m['sum'] = array_sum($m);
		return $m;
	}

	function update($posts, $action=null, $id=null, $tbname = null, $conditions = null){
		global $data;
		if(isset($data['Templet']['title'])){
			$cfg['templet_name'] = $data['Templet']['title'];
			$posts['configs'] = serialize($cfg);
		}
		return $this->save($posts, $action, $id, $tbname, $conditions);
	}

	function getTempletName($configs){
		$cfgs = unserialize($configs);
		return $cfgs['templet_name'];
	}

	function setConfigs($configs){
		$cfgs = unserialize($configs);
		$this->configs = $cfg;
	}

	function getConfigs(){
		return $this->configs;
	}

	function checkStatus($company_id)
	{
		$sql = "SELECT status FROM {$this->table_prefix}companies WHERE id='".$company_id."'";
		$c_status = $this->dbstuff->GetRow($sql);
		if (!$c_status['status'] || empty($c_status['status'])) {
			flash("company_checking", "company.php");
		}
	}
	
	function newCheckStatus($status)
	{
		if (!$status || empty($status)) {
			flash("company_checking", "company.php");
		}
	}
	
	function getInfoById($company_id)
	{
		$sql = "SELECT c.*,c.name as companyname,tel AS link_tel,cf.* FROM {$this->table_prefix}companies c LEFT JOIN {$this->table_prefix}companyfields cf ON c.id=cf.company_id WHERE c.id={$company_id}";
		$result = $this->dbstuff->GetRow($sql);
		$this->info = $result;
		return $result;
	}
	
	function Add($member_id = -1)
	{
		global $companyfield, $default_membergroupid;
		if (empty($this->params['data']['company']['name'])) {
			return false;
		}
		$this->params['data']['company']['member_id'] = $member_id;
		$this->params['data']['company']['created'] = $this->params['data']['company']['modified'] = $this->timestamp;
		$this->params['data']['company']['cache_spacename'] = $this->timestamp;
		$this->params['data']['company']['cache_membergroupid'] = $default_membergroupid;
		$this->save($this->params['data']['company']);
		$key = $this->table_name."_id";
		//$last_companyid = $this->$key;
		$last_companyid = $this->dbstuff->Insert_ID();
		$companyfield->primaryKey = "company_id";
		$companyfield->params['data']['companyfield']['company_id'] = $last_companyid;
		$companyfield->save($companyfield->params['data']['companyfield']);
		return true;
	}
	
	function checkNameExists($company_name)
	{
		$result = $this->field("id", "name='".$company_name."'");
		if (empty($result) || !$result) {
			return false;
		}else{
			return true;
		}
	}
	
	function updateCachename($id, $new_name)
	{
		$old_name = $this->dbstuff->GetOne("SELECT name FROM {$this->table_prefix}companies WHERE id=".$id);
		if (pb_strcomp($old_name, $new_name)) {
			return;
		}
		$this->dbstuff->Execute("UPDATE {$this->table_prefix}products p SET p.cache_companyname='".$new_name."' WHERE p.company_id=".$id);
		$this->dbstuff->Execute("UPDATE {$this->table_prefix}trades t SET t.cache_companyname='".$new_name."' WHERE t.company_id=".$id);
	}
	
	function formatResult($result)
	{
		require(CACHE_COMMON_PATH. "cache_typeoption.php");
		$G['membergroup'] = cache_read("membergroup");
		if (!class_exists('Space')) {
			uses("space");
		}
		$space_controller = new Space();
		if (!$result || empty($result)) {
			return null;
		}
		$count = count($result);
		for ($i=0; $i<$count; $i++){
			$result[$i]['gradeimg'] = STATICURL. 'images/group/'.$G['membergroup'][$result[$i]['cache_membergroupid']]['avatar'];
			if(!empty($result[$i]['manage_type'])) $result[$i]['managetype'] = $_PB_CACHE['manage_type'][$result[$i]['manage_type']];
			if(!empty($result[$i]['membergroup_id'])) $result[$i]['gradename'] = $G['membergroup'][$result[$i]['membergroup_id']]['name'];
			if (isset($result[$i]['space_name'])) {
				$result[$i]['url'] = $space_controller->rewrite($result[$i]['space_name'], $result[$i]['id']);
			}else{
				$result[$i]['url'] = "javascript:;";
			}
			if (isset($result[$i]['picture'])) {
				$result[$i]['logo'] = pb_get_attachmenturl($result[$i]['picture'], '', 'small');
				$result[$i]['logosrc'] = '<img alt="'.$result[$i]['name'].'" src="'.pb_get_attachmenturl($result[$i]['picture'], '', 'small').'" />';
			}
		}
		return $result;		
	}
	
	function getPhone($code = 0, $zone = 0, $id = 0)
	{
		$p = null;
		if (!empty($code)) {
			$p.="(".$code.")";
		}else{
			$p.="(000)";
		}
		if (empty($zone)) {
			$zone = "00";
		}
		$p.=@implode("-", array($zone, $id));
		return trim($p);
	}
	
	function splitPhone($phone)
	{
		$return = array();
		preg_match("/\(+([0-9]{2,3})+\)([0-9]{1,3})-([0-9]{1,8})/", $phone, $return);
		return $return;
	}
}
?>