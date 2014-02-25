<?php
class Job extends PbController {
	var $name = "Job";
	
	function __construct()
	{
		$this->loadModel("job");
	}

	function index()
	{
		$salaries = cache_read('typeoption', 'salary');
		setvar("Salary", $salaries);
		render("job/index");
	}

	function lists()
	{
		global $viewhelper, $pos;
		using("industry", "area");
		$area = new Areas();
		$industry = new Industries();
		$conditions[] = "Job.status=1";
		$viewhelper->setTitle(L("hr_information", "tpl"));
		$viewhelper->setPosition(L("hr_information", "tpl"), "index.php?do=job&action=".__FUNCTION__);
		if(!empty($_GET['q'])){
			$title = trim($_GET['q']);
			$conditions[]= "Job.name like '%".$title."%'";
		}
		if (!empty($_GET['data']['salary_id'])) {
			$conditions[] = "Job.salary_id=".intval($_GET['data']['salary_id']);
		}
		if (!empty($_GET['data']['area_id'])) {
			$conditions[] = "Job.area_id=".intval($_GET['data']['area_id']);
		}
		if (isset($_GET['industryid'])) {
			$industry_id = intval($_GET['industryid']);
			$tmp_info = $industry->setInfo($industry_id);
			if (!empty($tmp_info)) {
				$conditions[] = "Job.industry_id=".$tmp_info['id'];
				$viewhelper->setTitle($tmp_info['name']);
				$viewhelper->setPosition($tmp_info['name'], "index.php?do=job&action=".__FUNCTION__."&industryid=".$tmp_info['id']);
			}
		}
		if (isset($_GET['areaid'])) {
			$area_id = intval($_GET['areaid']);
			$tmp_info = $area->setInfo($area_id);
			if (!empty($tmp_info)) {
				$conditions[] = "Job.area_id=".$tmp_info['id'];
				$viewhelper->setTitle($tmp_info['name']);
				$viewhelper->setPosition($tmp_info['name'], "index.php?do=job&action=".__FUNCTION__."&areaid=".$tmp_info['id']);
			}
		}
		$amount = $this->job->findCount(null, $conditions, "Job.id");
		$result = $this->job->findAll("Job.*,Job.cache_spacename AS userid,Job.created AS pubdate,(select Company.name from ".$this->job->table_prefix."companies Company where Company.id=Job.id) AS companyname", null, $conditions, "Job.id DESC", $pos, $this->displaypg);
		$viewhelper->setTitle(L("search", "tpl"));
		$viewhelper->setPosition(L("search", "tpl"));
		setvar("items", $result);
		setvar("paging", array('total'=>$amount));
		render("job/list", 1);
	}
}
?>