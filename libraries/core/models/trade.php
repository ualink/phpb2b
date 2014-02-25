<?php
class Trades extends PbModel {
 	var $name = "Trade";
 	var $info;
 	var $fields = "m.space_name as userid,m.membertype_id,m.username,m.trusttype_ids,m.credits,m.membergroup_id,t.*,t.content as digest,t.cache_companyname as companyname";
 	var $amount;
 	public static $instance = NULL;
	var $condition;

 	function __construct()
 	{
		parent::__construct();
 	}
 	
 	function initSearch()
 	{
 		global $G;
 		$this->condition[] = "status=1";
 		if (isset($_GET['q'])) {
 			$searchkeywords = $_GET['q'];
 			$this->condition[]= "title like '%".$searchkeywords."%'";
 		}
 		if (isset($_GET['pubdate'])) {
 			switch ($_GET['pubdate']) {
 				case "l3":
 					$this->condition[] = "submit_time>".($time_stamp-3*86400);
 					break;
 				case "l10":
 					$this->condition[] = "submit_time>".($time_stamp-10*86400);
 					break;
 				case "l30":
 					$this->condition[] = "submit_time>".($time_stamp-30*86400);
 					break;
 				default:
 					break;
 			}
 		}
 		if ($G['setting']['offer_expire_method']==2 || $G['setting']['offer_expire_method']==3) {
 			$trade->condition[] = "t.expire_time>".$time_stamp;
 		}
 		if (isset($_GET['type'])) {
 			if($_GET['type']=="urgent"){
 				$this->condition[]="if_urgent='1'";
 			}
 		}
 		if (isset($_GET['typeid'])) {
 			$type_id = intval($_GET['typeid']);
 			$this->condition[]= "type_id='".$type_id."'";
 		}
 		if (isset($_GET['industryid'])) {
 			if (strpos($_GET['industryid'], ",")!==false) {
 				$this->condition[]= "industry_id IN (".trim($_GET['industryid']).")";
 			}else{
	 			$industryid = intval($_GET['industryid']);
	 			$this->condition[]= "industry_id='".$industryid."'";
 			}
 		}
 		if (isset($_GET['areaid'])) {
 			if (strpos($_GET['areaid'], ",")!==false) {
 				$this->condition[]= "area_id IN (".trim($_GET['areaid']).")";
 			}else{
	 			$areaid = intval($_GET['areaid']);
	 			$this->condition[]= "area_id='".$areaid."'";
 			}
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
 		global $viewhelper, $cache_types;
 		$result = $this->findAll("*,content AS digest", null, null, $this->orderby, $firstcount, $displaypg);
 		while(list($keys,$values) = each($result)){
 			$result[$keys]['pubdate'] = df($values['submit_time']);
 			$result[$keys]['title'] = pb_lang_split($values['title']);
 			$result[$keys]['content'] = pb_lang_split($values['content']);
 			$result[$keys]['digest'] = pb_lang_split($values['digest']);
 			$result[$keys]['typename'] = $cache_types['offertype'][$values['type_id']];
 			$result[$keys]['thumb'] = pb_get_attachmenturl($values['picture'], '', 'small');
 			$result[$keys]['url'] = $this->url(array("do"=>"offer", "id"=>$values['id']));
 		}
 		return $result;
 	}
 	
	function getInstance() {
		$_this_class = get_class();
		if (!isset(self::$instance[get_class()])) {
			self::$instance = new $_this_class;
		}
		return self::$instance;
	}
 	
 	function checkExist($id, $extra = false)
 	{
 		$id = intval($id);
 		$info = $this->dbstuff->GetRow("SELECT title FROM {$this->table_prefix}trades WHERE id={$id}");
 		if (empty($info) or !$info) {
 			return false;
 		}else{
 			return true;
 		}
 	}
 	
 	function getInfoByCondition($id, $conditions = null)
 	{
 		$sql = "select tf.*,t.* from {$this->table_prefix}trades t left join {$this->table_prefix}tradefields tf on  tf.trade_id=t.id WHERE t.id=".intval($id).$conditions;
 		return $this->dbstuff->GetRow($sql);
 	}
 	
	function getInfoById($pid, $conditions = null)
	{
		$sql = "select tf.*,t.*,t.submit_time AS pubdate,expire_time AS expdate from {$this->table_prefix}trades t left join {$this->table_prefix}tradefields tf on  tf.trade_id=t.id WHERE t.id=".$pid.$conditions;
		$result = $this->dbstuff->GetRow($sql);
		$result['tel'] = $result['prim_telnumber'];
		$result['title'] = pb_lang_split($result['title']);
		$result['content'] = pb_lang_split($result['content']);
		if (!empty($result['picture'])) {
			$result['image'] = pb_get_attachmenturl($result['picture'], '');
			$result['image_url'] = rawurlencode($result['picture']);
		}
		if (!empty($result['tag_ids'])) {
			if (!class_exists("Tags")) {
				uses("tag");
				$tag = new Tags();
			}else{
				$tag = new Tags();
			}
			$tag_res = $tag->getTagsByIds($result['tag_ids']);
			if (!empty($tag_res)) {
				$tags = null;
				foreach ($tag_res as $key=>$val){
					$tags.='<a href="index.php?do=offer&action=lists&q='.urlencode($val).'" target="_blank">'.urldecode($val).'</a>&nbsp;';	
				}
				$result['tag'] = $tags;
				unset($tags, $tag_res, $tag);
			}
		}
		return $result;
	}

	function checkAccess($trade_info_un){
		$trade_info = unserialize($trade_info_un);
		global $tmp_status;
		global $pb_userinfo;
		if($trade_info['TradeStatus']!=1){
			$tmp_key = intval($trade_info['TradeStatus']);
			flash(urlencode($trade_info['Name'].$tmp_status[$tmp_key]));
		}
		if($trade_info['require_membertype']>0){
			if(empty($pb_userinfo['user_type'])) {
				flash("no_perm");
			}
		}
		$t_point = intval($trade_info['require_point']);
		if($t_point>0){
			if($pb_userinfo['points']<$t_point){
			    flash("not_enough_point");
			}else{
			    $sql = "update {$this->table_prefix}members set credits=credits-".$t_point;
			    $this->dbstuff->Execute($sql);
			}
		}
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
		$this->dbstuff->Execute("DELETE FROM {$this->table_prefix}trades,{$this->table_prefix}tradefields USING {$this->table_prefix}trades LEFT JOIN {$this->table_prefix}tradefields ON {$this->table_prefix}tradefields.trade_id={$this->table_prefix}trades.id ".$this->getCondition());
		$this->condition = null;
		return true;
	}
	
	function Add($params = '')
	{
		$result = false;
		if (!empty($this->params['expire_days'])) {
			$trade_controller = Trade::getInstance();
			if (array_key_exists($this->params['expire_days'],$trade_controller->getOfferExpires())) {
				$this->params['data']['trade']['expire_time'] = $this->timestamp+(24*3600*$_POST['expire_days']);
				$this->params['data']['trade']['expire_days'] = $_POST['expire_days'];
			}else{
				$this->params['data']['trade']['expire_time'] = $this->timestamp+(24*3600*10);
				$this->params['data']['trade']['expire_days'] = 10;
			}
		}
		$this->params['data']['trade']['submit_time'] = $this->params['data']['trade']['created'] = $this->params['data']['trade']['modified'] = $this->timestamp;
		$this->params['data']['trade']['ip_addr'] = pb_get_client_ip('str');
		if (isset($this->params['data']['trade']['title'])) {
			$trade_info = $this->params['data']['trade'];
		    $result = $this->save($trade_info);
		    $key = $this->table_name."_id";
		    //$last_tradeid = $this->$key;
		    $last_tradeid = $this->dbstuff->Insert_ID();
			$_this = Tradefields::getInstance();
			$_this->params['data']['tradefield']['trade_id'] = $last_tradeid;
			$tradefield_info = $_this->params['data']['tradefield']+$this->params['data']['tradefield'];
			$_this->primaryKey = "trade_id";
			$_this->save($tradefield_info);
		}
		return $result;
	}
	
	function refresh($ids)
	{
		if (empty($ids)) {
			return false;
		}
		if (is_array($ids)) {
			$condition = "id IN (".implode(",", $ids).")";
		}else{
			$condition = "id=".$ids;
		}
		return $this->dbstuff->Execute("UPDATE {$this->table_prefix}trades SET expire_time=expire_days*86400+".$this->timestamp.",submit_time=".$this->timestamp." WHERE ".$condition);
	}
	
	function formatIM($record)
	{
		$return = $code = null;
		$record = unserialize($record);
		if (!is_array($record)) {
			return false;
		}
		foreach ($record as $key=>$val) {
			if(!empty($val)) {
				switch (strtolower($key)) {
					case "qq":
						$code = 'href="http://wpa.qq.com/msgrd?V=1&Uin='.$val.'&Site='.URL.'&Menu=yes"';
						break;		
					case "skype":
						$code = 'href="skype:'.$val.'?call" onclick="return skypeCheck();"';
						break;		
					case "msn":
						$code = 'href="msnim:chat?contact='.$val.'"';
						break;
					case "icq":
						$code = 'href="http://wwp.icq.com/scripts/search.dll?to='.$val.'"';
						break;
					case "yahoo":
						$code = 'href="http://edit.yahoo.com/config/send_webmesg?.target='.$val.'&.src=pg"';
						break;
					default:
						$code = $val;
						break;
				}
				$return.='<a '.$code.' target="_blank"><span class="im_'.$key.'">'.strtoupper($key).'</span></a>';
			}
		}
		return $return;
	}
	
	function formatResult($result)
	{
		$trusttypes = cache_read("trusttype");
		$countries = cache_read("country");
		$membergroups = cache_read("membergroup");
		if(class_exists("Trade")){
			$trade_controller = new Trade();
		}else{
			uses("trade");
			$trade_controller = new Trade();
		}
		if(class_exists("Form")){
			$form = new Forms();
		}else{
			uses("trade");
			$form = new Forms();
		}
		if(!empty($result)){
			if (empty($trusttypes)) {
				$trusttypes = cache_read("trusttype");
			}
			$result_count = count($result);
			for ($i=0; $i<$result_count; $i++){
				if (empty($result[$i]['userid'])) {
					$result[$i]['userid'] = $result[$i]['username'];
				}
				if(!empty($result[$i]['formattribute_ids'])) {
					$tmp_arr = $form->getAttribute(explode(",", $result[$i]['formattribute_ids']));
					if(!empty($tmp_arr)){
						foreach ($tmp_arr as $key=>$val) {
							$result[$i][$key] = $val;
						}
					}
				}
				if (!empty($result[$i]['country_id'])) {
					$result[$i]['country'] = pb_get_attachmenturl($countries[$result[$i]['country_id']]['picture'], '', 'country');
				}
				$result[$i]['im'] = $this->formatIM($result[$i]['cache_contacts']);
				$result[$i]['pubdate'] = df($result[$i]['submit_time']);
				$result[$i]['content'] = strip_tags(pb_lang_split($result[$i]['content']));
				$result[$i]['title'] = pb_lang_split($result[$i]['title']);
				if(!empty($result[$i]['membergroup_id'])) {
					$result[$i]['gradeimg'] = pb_get_attachmenturl($membergroups[$result[$i]['membergroup_id']]['avatar'], '', 'group');
					$result[$i]['gradename'] = $membergroups[$result[$i]['membergroup_id']]['name'];
				}
				$result[$i]['image'] = pb_get_attachmenturl($result[$i]['picture'], '', 'small');
				$trusttype_images = null;
				if(!empty($result[$i]['trusttype_ids'])){
					$tmp_trusttype = explode(",", $result[$i]['trusttype_ids']);
					foreach ($tmp_trusttype as $val) {
						$trusttype_images.='<img src="'.$trusttypes[$val]['avatar'].'" alt="'.$trusttypes[$val]['name'].'" />';
					}
				}
				$result[$i]['trusttype'] = $trusttype_images;
			}
			return $result;
		}else{
			return null;
		}
	}
	
	function getRenderDatas($conditions = null, $filter = false)
	{
		global $pos;
		if (!empty($conditions)) {
			$this->setCondition($conditions);
		}
		$sql = "SELECT ".$this->fields." FROM ".$this->table_prefix."trades t LEFT JOIN ".$this->table_prefix."members m ON m.id=t.member_id ".$this->getCondition()." ORDER BY t.id DESC LIMIT {$pos},{$this->displaypg}";
		if (!isset($_GET['pos']) && !empty($amount)) {
			if ($amount>$limit && $filter) {
				$sql = "SELECT ".$this->fields." FROM ".$this->table_prefix."trades t LEFT JOIN ".$this->table_prefix."members m ON m.id=t.member_id ".$this->getCondition()." GROUP BY t.member_id ORDER BY t.id DESC LIMIT {$pos},{$limit}";
			}
		}
		$result = $this->dbstuff->GetArray($sql);
		return $this->formatResult($result);
	}
	
	function getStickyDatas()
	{
		$condition = null;
		if (!isset($_GET['page']) || $_GET['page']==1) {
			if (isset($_GET['typeid'])) {
				$type_id = intval($_GET['typeid']);
				$condition = " AND t.type_id='".$type_id."'";
			}
			$sql = "SELECT ".$this->fields." FROM ".$this->table_prefix."trades t LEFT JOIN ".$this->table_prefix."members m ON m.id=t.member_id WHERE t.display_order>0 {$condition} AND t.display_expiration>".$this->timestamp." ORDER BY t.id DESC LIMIT 0,5";
			$result = $this->dbstuff->GetArray($sql);
			if(!empty($result)){
				return $this->formatResult($result);
			}
		}
		return false;
	}
}
?>