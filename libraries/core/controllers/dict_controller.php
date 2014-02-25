<?php
class Dict extends PbController {
	var $name = "Dict";
	
	function __construct()
	{
		$this->loadModel("dict");
		$this->loadModel("dicttype");
	}
	
	function index()
	{
		global $viewhelper, $pos;
		$conditions = array();
		$viewhelper->setPosition(L("dictionary", "tpl"), "index.php?do=dict");
		$viewhelper->setTitle(L("dictionary", "tpl"));
		if (isset($_GET['action'])) {
			$action = trim($_GET['action']);
			if ($action == "search") {
				if (!empty($_GET['q'])) {
					$conditions[] = "word like '%".$_GET['q']."%'";
				}
				if (isset($_GET['typeid'])) {
					$type_id = intval($_GET['typeid']);
					$conditions[] = "dicttype_id='".$type_id."'";
				}
				$amount = $this->dict->findCount(null, $conditions);
				$result = $this->dict->findAll("Dict.*,dp.name AS typename", array("LEFT JOIN {$this->dict->table_prefix}dicttypes dp ON dp.id=Dict.dicttype_id"), $conditions, "Dict.id DESC", $pos, $this->displaypg);
				if (!empty($result)) {
					setvar("items", $result);
					setvar("paging", array('total'=>$amount));
				}
				render("dict/list", true);
			}
		}
		//get dictionary types.
		$dict_types = $this->dicttype->getAllTypes();
		$dict_types = pb_lang_split_recursive($dict_types);
		setvar("Dictypes", $dict_types);
		render("dict/index", true);
	}
	
	function detail()
	{
		global $viewhelper;
		$id = $wd = '';
		$viewhelper->setPosition(L("dictionary", "tpl"), "index.php?do=dict");
		$viewhelper->setTitle(L("dictionary", "tpl"));
		if (isset($_GET['id'])) {
			$id = intval($_GET['id']);
		}
		if (isset($_GET['wd'])) {
			$wd = trim($_GET['wd']);
		}
		$result = $this->dict->getInfo($id, $wd);
		if (!empty($result)) {
			$viewhelper->setPosition($result['typename'], "index.php?do=dict&action=search&typeid=".$result['dicttype_id']);
			$viewhelper->setTitle($result['word']);
			$viewhelper->setPosition($result['word']);
			$result['typename'] = pb_lang_split($result['typename']);
			setvar("item", $result);
			$this->dict->dbstuff->Execute("UPDATE {$this->dict->table_prefix}dicts SET hits=hits+1 WHERE id=".$id."");
			render("dict/detail", true);
		}else{
			flash("data_not_exists");
		}
	}
	
	function lists()
	{
		global $viewhelper, $pos;
		$conditions = array();
		$viewhelper->setPosition(L("dictionary", "tpl"), "index.php?do=dict");
		$viewhelper->setTitle(L("dictionary", "tpl"));
		if (!empty($_GET['q'])) {
			$conditions[] = "word like '%".$_GET['q']."%'";
		}
		if (isset($_GET['typeid'])) {
			$type_id = intval($_GET['typeid']);
			$conditions[] = "dicttype_id='".$type_id."'";
		}
		$amount = $this->dict->findCount(null, $conditions);
		$result = $this->dict->findAll("Dict.*,dp.name AS typename", array("LEFT JOIN {$this->dict->table_prefix}dicttypes dp ON dp.id=Dict.dicttype_id"), $conditions, "Dict.id DESC", $pos, $this->displaypg);
		if (!empty($result)) {
			setvar("items", $result);
			setvar("paging", array('total'=>$amount));
		}
		render("dict/list", true);
	}
}
?>