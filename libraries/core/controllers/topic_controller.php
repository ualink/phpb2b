<?php
class Topic extends PbController {
	var $name = "Topic";
	
	function __construct()
	{
		$this->loadModel("topic");
	}
	
	function index()
	{
		global $viewhelper;
		$viewhelper->setPosition(L("sub_special", "tpl"), "special/");
		$viewhelper->setTitle(L("sub_special", "tpl"));
		$tpl_file = "special/index";
		$membertypes = cache_read("type", "membertype");
		setvar("member_types", $membertypes);
		render($tpl_file);
	}
	
	function detail()
	{
		global $viewhelper, $smarty, $theme_name;
		$viewhelper->setPosition(L("sub_special", "tpl"), "special/");
		$viewhelper->setTitle(L("sub_special", "tpl"));
		$types = cache_read("type");
		$tpl_file = "special/index";
		if (!empty($_GET['id'])) {
			$sql = "SELECT * FROM ".$this->topic->table_prefix."topics WHERE id='".intval($_GET['id'])."'";
			$topic_info = $this->topic->dbstuff->GetRow($sql);
		}else{
			flash("data_not_exists", URL);
		}
		if (!empty($topic_info['templet'])) {
			//delete .ext
			$name_count = strpos($topic_info['templet'], ".");
			if ($name_count>0){
				$tf = "special/".substr($topic_info['templet'], 0, $name_count);
			}else{
				$tf = "special/".$topic_info['templet'];
			}
			if ($viewhelper->tpl_exists($smarty->template_dir.$theme_name.DS.$tf.$smarty->tpl_ext)) {
				$tpl_file = $tf;
			}
		}
		$viewhelper->setTitle($topic_info['title']);
		$viewhelper->setMetaDescription(mb_substr($topic_info['description'], 0, 100));
		$viewhelper->setMetaKeyword($topic_info['title']);
		setvar("topic", $topic_info);
		$membertypes = cache_read("type", "membertype");
		setvar("member_types", $membertypes);
		render($tpl_file);
	}
	
	function area()
	{
		$tpl_file = "special/list";
		$mod = __FUNCTION__;
		global $viewhelper;
		$datas = cache_read($mod);
		$datas = PbController::getInstance()->array_multi2single($datas);
		$alphabet_sorts = array();
		foreach ($datas as $key=>$val) {
			$letter =  strtoupper(PbController::getInitial($val));
			$alphabet_sorts[$letter]['child'][$key]['id'] = $key;
			$alphabet_sorts[$letter]['child'][$key]['title'] = $val;
		}
		ksort($alphabet_sorts);
		setvar("alpha_datas", $alphabet_sorts);
		setvar("type", $mod);
		setvar("station_name", L("sub_".$mod, "tpl"));
		unset($datas, $alphabet_sorts);
		$viewhelper->setTitle(L("sub_".$mod, "tpl"));
		$membertypes = cache_read("type", "membertype");
		setvar("member_types", $membertypes);
		render($tpl_file);
	}
	
	function industry()
	{
		$tpl_file = "special/list";
		$mod = __FUNCTION__;
		global $viewhelper;
		$datas = cache_read($mod);
		$datas = PbController::getInstance()->array_multi2single($datas);
		$alphabet_sorts = array();
		foreach ($datas as $key=>$val) {
			$letter =  strtoupper(PbController::getInitial($val));
			$alphabet_sorts[$letter]['child'][$key]['id'] = $key;
			$alphabet_sorts[$letter]['child'][$key]['title'] = $val;
		}
		ksort($alphabet_sorts);
		setvar("alpha_datas", $alphabet_sorts);
		setvar("type", $mod);
		setvar("station_name", L("sub_".$mod, "tpl"));
		unset($datas, $alphabet_sorts);
		$viewhelper->setTitle(L("sub_".$mod, "tpl"));
		$membertypes = cache_read("type", "membertype");
		setvar("member_types", $membertypes);
		render($tpl_file);		
	}
	
	function lists()
	{
		global $viewhelper;
		$conditions = array();
		$viewhelper->setPosition(L("sub_special", "tpl"), "index.php?do=topic");
		$viewhelper->setTitle(L("sub_special", "tpl"));
		$types = cache_read("type");
		$result = $this->topic->findAll("*", null, $conditions, "id DESC");
		if (!empty($result)) {
			for($i=0; $i<count($result); $i++){
				if (!empty($result[$i]['created'])) {
					$result[$i]['pubdate'] = date("Y-m-d", $result[$i]['created']);
					$result[$i]['image'] =  pb_get_attachmenturl($result[$i]['picture'], '', 'small');
				}
			}
			setvar("module", strtolower(get_class($this)));
			setvar("items", $result);
		}
		render("list.default");
	}
}
?>