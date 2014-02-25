<?php
class Help extends PbController {
	var $name = "Help";
	var $types;
	
	function __construct()
	{
		$this->loadModel("help");
		$this->loadModel("helptype");
		$helptype_result = $this->helptype->findAll("id,title",null, "parent_id=0","display_order ASC,id DESC");
		if (!empty($helptype_result)) {
			foreach ($helptype_result as $key=>$val) {
				$helptype_result[$val['id']]['id'] = $val['id'];
				$helptype_result[$val['id']]['name'] = $val['title'];
				$sub_result = $this->helptype->dbstuff->GetArray("SELECT id,title FROM {$this->helptype->table_prefix}helptypes WHERE parent_id='".$val['id']."'");
				if (!empty($sub_result)) {
					foreach ($sub_result as $key1=>$val1) {
						$helptype_result[$val['id']]['sub'][$val1['id']]['id'] = $val1['id'];
						$helptype_result[$val['id']]['sub'][$val1['id']]['name'] = pb_lang_split($val1['title']);
					}
				}
			}
//			$this->types = $helptype_result;
			setvar("Helptypes", pb_lang_split_recursive($helptype_result));
		}
	}
	
	function index()
	{
		global $viewhelper;
		$tpl_file = "help/index";
		$viewhelper->setPosition(L("help_center", "tpl"), "index.php?do=help");
		$viewhelper->setTitle(L("help_center", "tpl"));
		$result = $this->help->findAll("id,title", null, null, "id DESC");
		setvar("Items", pb_lang_split_recursive($result));
		render($tpl_file);
	}
	
	function lists()
	{
		global $viewhelper;
		$conditions = array();
		$tpl_file = "help/index";
		$viewhelper->setPosition(L("help_center", "tpl"), "index.php?do=help");
		$viewhelper->setTitle(L("help_center", "tpl"));
		if(isset($_GET['typeid'])) {
			$type_id = intval($_GET['typeid']);
			$conditions[] = "helptype_id=".$type_id;
			$type_name = $this->help->dbstuff->GetOne("SELECT title FROM {$this->help->table_prefix}helptypes WHERE id='".$type_id."'");
			$viewhelper->setTitle($type_name);
			$viewhelper->setPosition($type_name, "index.php?do=help&action=lists&typeid=".$type_id);
		}
		if (!empty($_GET['q'])) {
			$conditions[] = "title like '%".trim($_GET['q'])."%'";
		}
		$result = $this->help->findAll("id,title", null, $conditions, "id DESC");
		setvar("Items", pb_lang_split_recursive($result));
		render($tpl_file);
	}
	
	function detail()
	{
		global $viewhelper;
		$tpl_file = "help/detail";
		$viewhelper->setTitle(L("help_center", "tpl"));
		$viewhelper->setPosition(L("help_center", "tpl"), "index.php?do=help");
		if (isset($_GET['id'])) {
			$id = intval($_GET['id']);
			$help_result = $this->help->dbstuff->GetRow("SELECT * FROM {$this->help->table_prefix}helps WHERE id=".$id);
			if (!empty($help_result)) {
				$title = pb_lang_split($help_result['title']);
				$viewhelper->setTitle($title);
				$viewhelper->setPosition($title);
				setvar("item", $help_result);
			}
		}
		render($tpl_file);
	}
}
?>