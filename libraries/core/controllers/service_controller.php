<?php
class Service extends PbController {
	var $name = "Service";
	
	function __construct()
	{
		$this->loadModel("service");
	}
	
	function index()
	{
		require(CACHE_COMMON_PATH. "cache_typeoption.php");
		$answered_result = $this->service->findAll("id,title,created,revert_content,revert_date,type_id", null, "status=1 AND revert_content!=''", "id DESC", 0, 15);
		$result = $this->service->findAll("id,title,created,revert_content,revert_date,type_id", null, "status=1", "id DESC", 0, 15);
		setvar("AnsweredService", $this->service->formatResult($answered_result, $_PB_CACHE['service_type']));
		setvar("LatestService", $this->service->formatResult($result, $_PB_CACHE['service_type']));
		setvar("ServiceTypes", $_PB_CACHE['service_type']);
		render("service/index");		
	}
	
	function detail()
	{
		global $viewhelper;
		require(CACHE_COMMON_PATH. "cache_typeoption.php");
		$viewhelper->setTitle(L("customer_service_center", "tpl"));
		$viewhelper->setPosition(L("customer_service_center", "tpl"), "index.php?do=service");
		if (isset($_GET['id'])) {
			$id = intval($_GET['id']);
			$result = $this->service->findById($id);
			if (!empty($result)) {
				$result['revertdate'] = date("Y-m-d H:i", $result['revert_date']);
				$viewhelper->setPosition($_PB_CACHE['service_type'][$result['type_id']], "index.php?do=service&action=lists&typeid=".$result['type_id']);
				$viewhelper->setTitle($result['title']);
				$viewhelper->setPosition($result['title']);
				setvar("item", $result);
				render("service/detail");
			}
		}
	}
	
	function lists()
	{
		global $viewhelper;
		require(CACHE_COMMON_PATH. "cache_typeoption.php");
		$conditions[] = "status=1";
		$viewhelper->setPosition(L("customer_service_center", "tpl"), "index.php?do=service");
		$viewhelper->setTitle(L("customer_service_center", "tpl"));
		if (isset($_GET['typeid'])) {
			$type_id = intval($_GET['typeid']);
			$conditions[] = "type_id=".$type_id;
			setvar("TypeName", $_PB_CACHE['service_type'][$type_id]);
			$viewhelper->setPosition($_PB_CACHE['service_type'][$type_id]);
			$viewhelper->setTitle($_PB_CACHE['service_type'][$type_id]);
		}
		$amount = $this->service->findCount(null, $conditions,"id");
		$result = $this->service->findAll("id,title,created,revert_content,revert_date,type_id", null, $conditions, "id DESC", $pos, $this->displaypg);
		setvar("items", $this->service->formatResult($result, $_PB_CACHE['service_type']));
		setvar("ServiceTypes", $_PB_CACHE['service_type']);
		setvar("paging", array('total'=>$amount));
		render("service/list");		
	}
	
	function client()
	{
		global $viewhelper, $G;
		setvar("item", $G['setting']);
		$viewhelper->setTitle(L("customer_service_center", "tpl"));
		$viewhelper->setPosition(L("customer_service_center", "tpl"), "index.php?do=service");
		$viewhelper->setTitle(L("service_client", "tpl"));
		$viewhelper->setPosition(L("service_client", "tpl"));
		render("service/client");
	}
	
	function post()
	{
		require(CLASS_PATH. "validation.class.php");
		$validate = new Validation();
		if (isset($_POST['save_service'])) {
			pb_submit_check('service');
			$vals = array();
			$vals['status'] = 0;
			$vals['member_id'] = 0;
			$vals['content'] = $_POST['service']['content'];
			if(isset($_POST['service']['nick_name'])) $vals['nick_name'] = $_POST['service']['nick_name'];
			$vals['email'] = $_POST['service']['email'];
			$vals['type_id'] = $_POST['service']['type_id'];
			$vals['created'] = $time_stamp;
			$vals['user_ip'] = pb_get_client_ip();
			$vals['title'] = $_POST['service']['title'];
			$this->service->doValidation($vals);
			if (!empty($this->service->validationErrors)) {
				setvar("item", $vals);
				setvar("Errors", $validate->show($service));
				render("service/index");
			}else{
				if (empty($vals['title'])) {
					$vals['title'] = L("comments_and_suggestions", "tpl");
				}
				if($this->service->save($vals)){
					flash('thanks_for_advise', URL);
				}else {
					flash();
				}
			}
		}else{
			flash("pls_enter_your_advise", "index.php");
		}
	}
}
?>