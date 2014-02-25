<?php
class Company extends PbController {
	var $name = "Company";
	
	function __construct()
	{
		global $viewhelper;
		$this->viewhelper = $viewhelper;
	}
	
	function index()
	{
		render("company/index", 1);		
	}
	
	function detail()
	{
		global $G;
		using("area","industry");
		$area = new Areas();
		$industry = new Industries();
		$tpl_file = "company/detail";
		$this->viewhelper->setTitle(L("yellow_page", "tpl"));
		$this->viewhelper->setPosition(L("yellow_page", "tpl"), "index.php?do=company");
		if (isset($_GET['id'])) {
			$id = intval($_GET['id']);
			$result = $area->dbstuff->GetRow("SELECT * FROM {$area->table_prefix}companies WHERE id='".$id."'");
			if (!empty($result)) {
				$login_check = 1;//default open
				if(isset($G['setting']['company_logincheck'])) $login_check = $G['setting']['company_logincheck'];
				$this->viewhelper->setTitle($result['name']);
				$this->viewhelper->setPosition($result['name']);
				$result['tel'] = pb_hidestr(preg_replace('/\((.+?)\)/i', '', $result['tel']));
				$result['fax'] = pb_hidestr(preg_replace('/\((.+?)\)/i', '', $result['fax']));
				$result['mobile'] = pb_hidestr($result['mobile']);
				$result['industry_names'] = $industry->disSubNames($result['industry_id'], null, true, "company");
				$result['area_names'] = $area->disSubNames($result['area_id'], null, true, "company");
				setvar("item", $result);
				setvar("LoginCheck", $login_check);
			}
		}
		render($tpl_file, 1);
	}
}
?>