<?php
class Wap extends PbController {
	var $name = "Wap";
	var $allowed_search;
	var $model;
	var $module;
	var $model_name;
	var $extra;
	var $condition;
	var $option;
	
	function __construct()
	{
		parent::__construct();
		$this->model = new PbModel();
		$this->allowed_search = array("info"=>"news", "offer"=>"offer", "product"=>"product", "yellow_page"=>"company");
		if(isset($_GET['module'])) $this->module = trim(strtolower($_GET['module']));
		$this->option = $this->module;
		if (!empty($this->module) && !in_array($this->module, $this->allowed_search)) {
			flash();
		}
		$search_word = !empty($_GET['q']) ? htmlspecialchars(trim($_GET['q']), ENT_QUOTES) : '';
		switch ($this->module) {
			case "company":
				$this->condition[] = 'status=1';
				if (!empty($search_word))  $this->condition[] = "name LIKE '%{$search_word}%'";
				$this->extra = ",name AS title,description AS content";
				break;
			case "offer":
				$condition[] = 'status=1';
				if (!empty($search_word))  $this->condition[] = "title LIKE '%{$search_word}%'";
				$this->option = "offer";
				$this->module = "trade";
				break;
			case "product":
				$condition[] = 'status=1';
				if (!empty($search_word))  $this->condition[] = "name LIKE '%{$search_word}%'";
				$this->extra = ",name AS title";
				break;
			default:
				$condition[] = 'status=1';
				if (!empty($search_word))  $this->condition[] = "title LIKE '%{$search_word}%'";
				break;
		}
		$search_controller = new PbController();
		$this->model_name = htmlspecialchars($this->module, ENT_QUOTES);
		$this->model_name = strtolower($search_controller->pluralize(ucwords($this->model_name)));
		$this->view->setTemplateDir(PHPB2B_ROOT ."templates/wap/default".DS, 'wap');
		$this->view->setCompileDir(DATA_PATH."templates_c".DS.$this->lang.DS."wap".DS."default".DS);
	}
	
	function index()
	{
		require(PHPB2B_ROOT. "phpb2b_version.php");
		$tpl_file = "index";
		$this->view->display($tpl_file.$this->view->tpl_ext);
	}
	
	function search()
	{
		global $viewhelper;
		require(PHPB2B_ROOT. "phpb2b_version.php");
		$model_common = $this->model;
		$model_common->setCondition($this->condition);
		//pager
		$pagesize = 10;
		$page = isset($_GET["page"])?intval($_GET["page"]):1;
		$sql = "SELECT count(id) FROM ".$this->model->table_prefix.$this->model_name.$model_common->getCondition();
		$total = $this->model->dbstuff->GetOne($sql);
		$pagecount = ceil($total/$pagesize);
		if ($page>$pagecount){
		    $page = $pagecount;
		}
		if ($page<=0){
		    $page = 1;
		}
		$offset = ($page-1)*$pagesize;
		$pre = $page-1;
		$next = $page+1;
		$first = 1;
		$last = $pagecount;
		$op = null;
		if($page>1) $op.='<a href="?do=wap&action=search&module='.$this->option.'&page=1">'.L("first_page","tpl").'</a> <a href="?do=wap&action=search&module='.$this->option.'&page='.$pre.'">'.L("prev_page","tpl").'</a> ';
		if($page<$last) $op.= '<a href="?do='.$this->option.'&page='.$next.'">'.L("next_page","tpl").'</a> <a href="?do=wap&action=search&module='.$this->option.'&page='.$last.'">'.L("last_page","tpl").'</a> ';
		$op.='<strong>'.$page.'</strong>/'.$last;
		setvar("pages", $op);
		//:~
		$result = $this->model->dbstuff->GetArray("SELECT *".$this->extra." FROM ".$this->model->table_prefix.$this->model_name.$model_common->getCondition()." ORDER BY id DESC LIMIT ".$offset.",".$pagesize);
		$title = L(array_search($this->option, $this->allowed_search),'tpl');
		$viewhelper->setTitle($title);
		setvar("do_title", $title);
		setvar("items", $result);
		$tpl_file = "list";
		$this->view->display($tpl_file.$this->view->tpl_ext);		
	}
	
	function detail()
	{
		require(PHPB2B_ROOT. "phpb2b_version.php");
		if (!empty($_GET['id'])) {
			$id = intval($_GET['id']);
			$result = $this->model->dbstuff->GetRow("SELECT *".$this->extra." FROM ".$this->model->table_prefix.$this->model_name." WHERE id=".$id);
			setvar("item", $result);
			$tpl_file = "detail";
		}
		$this->view->display($tpl_file.$this->view->tpl_ext);
	}
}
?>