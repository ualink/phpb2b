<?php
class Standard extends PbController {
	var $name = "Standard";
	
	function __construct()
	{
		$this->loadModel("standard");
	}
	
	function lists()
	{
		global $viewhelper, $pos;
		$conditions = array();
		require(CACHE_COMMON_PATH."cache_type.php");
		$tpl_file = "list.default";
		$viewhelper->setTitle(L("standards", "tpl"));
		$viewhelper->setPosition(L("standards", "tpl"), "index.php?do=standard&action=lists");
		if (isset($_GET['typeid'])) {
			$typeid = intval($_GET['typeid']);
			$conditions[] = "type_id='".$typeid."'";
			$viewhelper->setTitle($type_name = $this->standard->dbstuff->GetOne("SELECT name FROM {$this->standard->table_prefix}standardtypes WHERE id='".$typeid."'"));
			$viewhelper->setPosition($type_name, "index.php?do=standard&action=".__FUNCTION__."&typeid=".$typeid);
			setvar("TypeName", $type_name);
		}
		if (isset($_GET['filter'])) {
			$filter = intval($_GET['filter']);
			$conditions[] = "created>".($this->standard->timestamp-$filter);
		}
		if (isset($_GET['q'])) {
			$searchkeywords = $_GET['q'];
			$conditions[] = "title like '%".$searchkeywords."%'";
		}
		$this->standard->setCondition($conditions);
		$amount = $this->standard->findCount(null, $conditions);
		$sql = "SELECT sd.* FROM {$this->standard->table_prefix}standards sd ".$this->standard->getCondition()." LIMIT ".$pos.",".$this->displaypg;
		$result = $this->standard->dbstuff->GetArray($sql);
		if(!empty($result)){
			$count = count($result);
			for ($i=0; $i<$count; $i++){
				$result[$i]['pubdate'] = df($result[$i]['created']);
				$result[$i]['typename'] = $_PB_CACHE['standardtype'][$result[$i]['type_id']];
				$result[$i]['typeid'] = $result[$i]['type_id'];
			}
		}
		setvar("module", "standard");
		setvar('paging', array('total'=>$amount));
		setvar('items', $result);
		render($tpl_file);
	}
	
	function index()
	{
		global $viewhelper, $pos;
		$conditions = null;
		require(CACHE_COMMON_PATH."cache_type.php");
		$tpl_file = "list.default";
		$viewhelper->setTitle(L("standards", "tpl"));
		$viewhelper->setPosition(L("standards", "tpl"), "index.php?do=standard");
		$this->standard->setCondition($conditions);
		$amount = $this->standard->findCount(null, $conditions);
		$sql = "SELECT sd.* FROM {$this->standard->table_prefix}standards sd ".$this->standard->getCondition()." LIMIT ".$pos.",".$this->displaypg;
		$result = $this->standard->dbstuff->GetArray($sql);
		if(!empty($result)){
			$count = count($result);
			for ($i=0; $i<$count; $i++){
				$result[$i]['pubdate'] = df($result[$i]['created']);
				$result[$i]['typename'] = $_PB_CACHE['standardtype'][$result[$i]['type_id']];
				$result[$i]['typeid'] = $result[$i]['type_id'];
			}
		}
		setvar("module", "standard");
		setvar('paging', array('total'=>$amount));
		setvar('items', $result);
		render($tpl_file);
	}
	
	function downloadtxt()
	{
		if (isset($_GET['id'])) {
			$id = intval($_GET['id']);
		}
		if (!empty($id)) {
			$info = $this->standard->read("*",$id);
			if (empty($info) or !$info) {
				flash("data_not_exists", '', 0);
			}
			$this->standard->downloadtxt($info);
		}
	}
	
	function detail()
	{
		global $viewhelper;
		require(CACHE_COMMON_PATH."cache_type.php");
		$tpl_file = "detail.default";
		$viewhelper->setTitle(L("standards", "tpl"));
		$viewhelper->setPosition(L("standards", "tpl"), "index.php?do=standard");
		if (isset($_GET['title'])) {
			$title = trim($_GET['title']);
			$res = $this->standard->findByTitle($title);
			$id = $res['id'];
		}
		if (isset($_GET['id'])) {
			$id = intval($_GET['id']);
		}
		if (!empty($id)) {
			$info = $this->standard->read("*",$id);
			if (empty($info) or !$info) {
				flash("data_not_exists", '', 0);
			}
			$info['pubdate'] = df($info['created']);
			$info['typename'] = $_PB_CACHE['standardtype'][$info['type_id']];
			$viewhelper->setTitle($info['typename']);
			$viewhelper->setPosition($info['typename'], "index.php?do=standard&action=lists&typeid=".$info['type_id']);
			$viewhelper->setTitle($info['title']);
			$viewhelper->setPosition($info['title']);
			if (!empty($info['attachment_id'])) {
				$info['attach_hashid'] = rawurlencode(authcode($info['attachment_id']));
			}
			$info['download_article'] = 1;
			//neighbour
			$neighbour_info = $this->standard->getNeighbour($id, "id,title");
			if (!empty($neighbour_info['prev'])) {
				$title = pb_lang_split($neighbour_info['prev']['title']);
				$info['prev_link'] = "<a href='".$this->url(array("module"=>"standard", "id"=>$neighbour_info['prev']['id']))."'>".$title."</a>";
				$info['prev_title'] = $title;
			}else{
				$info['prev_link'] = L("nothing", "tpl");
			}
			if (!empty($neighbour_info['next'])) {
				$title = pb_lang_split($neighbour_info['next']['title']);
				$info['next_link'] = "<a href='".$this->url(array("module"=>"standard", "id"=>$neighbour_info['next']['id']))."'>".$title."</a>";
				$info['next_title'] = $title;
			}else{
				$info['next_link'] = L("nothing", "tpl");
			}
			$info = pb_lang_split_recursive($info);
			setvar("item",$info);
			$this->standard->clicked($id);
			render($tpl_file,true);
		}
	}
}
?>