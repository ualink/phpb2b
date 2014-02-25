<?php
class Market extends PbController {
	var $name = "Market";
	
	function __construct()
	{
		$this->loadModel("market");
	}
	
	function index()
	{
		$conditions = "picture!=''";
		$this->market->setCondition($conditions);
		$amount = $this->market->findCount(null, $conditions);
		setvar("paging", array('total'=>$amount));
		render("market/index");
	}
	
	function add()
	{
		global $viewhelper;
		if (isset($_POST['do']) && !empty($_POST['data']['market']['name'])) {
			pb_submit_check("data");
			$this->market->setParams();
		    $this->market->params['data']['market']['industry_id'] = PbController::getMultiId($_POST['industry']['id']);
		    $this->market->params['data']['market']['area_id'] = PbController::getMultiId($_POST['area']['id']);
			$result = $this->market->Add();
			if ($result) {
				flash('thanks_for_adding_market');
			}else {
				pheader("location:add.php");
			}
		}
		$viewhelper->setPosition(L("added_market_info", "tpl"));
		render("market/add");		
	}
	
	function lists()
	{
		render("list.default");
	}
	
	function quote()
	{
		global $viewhelper, $pos;
		$this->loadModel("quote");
		$condition = $joins = $id = null;
		$conditions = array();
		$tpl_file = "market/quote";
		$viewhelper->setTitle(L("price_quotes", "tpl"));
		$viewhelper->setPosition(L("price_quotes", "tpl"), "index.php?do=market&action=quote");
		if (isset($_GET['id'])) {
			$id = intval($_GET['id']);
		}
		if (isset($_GET['catid'])) {
			$type_id = intval($_GET['catid']);
			$conditions[] = "Quote.type_id='".$type_id."'";
			$viewhelper->setTitle("The industry ".$type_id);
		}
		if (!empty($_GET['title'])) {
			$conditions[] = "title LIKE '%".pb_addslashes($_GET['title'])."%'";
		}
		$this->quote->setCondition($conditions);
		$amount = $this->quote->findCount(null, $conditions);
		$fields = "Quote.*,Quote.created AS pubdate,ROUND((Quote.min_price+Quote.max_price)/2,2) AS price";
		$result = $this->quote->findAll($fields, $joins, $conditions,"Quote.id DESC",$pos,$this->displaypg);
		setvar("items", pb_lang_split_recursive($result));
		uaAssign(array("QuoteSearchFrom"=>date("Y-m-d", strtotime("last month")), "QuoteSearchTo"=>date("Y-m-d")));
		setvar("paging", array('total'=>$amount));
		render($tpl_file);
	}
	
	function detail()
	{
		global $viewhelper;
		$viewhelper->setTitle(L("market", "tpl"));
		$viewhelper->setPosition(L("market", "tpl"), "index.php?do=market");
		if (isset($_GET['id'])) {
			$id = intval($_GET['id']);
			$sql = "select * from {$this->market->table_prefix}markets m where id={$id}";
			$item = $this->market->dbstuff->GetRow($sql);
		}
		if (!empty($item)) {
			if (isset($item['title'])) {
				$item['name'] = $item['title'];
			}
			$viewhelper->setMetaDescription($item['content']);
			$item['content'] = nl2br($item['content']);
			$viewhelper->setTitle($item['name']);
			$viewhelper->setPosition($item['name']);
			if (isset($item['status'])) {
				if($item['status']==0){
					$item['content'] = L('under_checking', 'msg', $item['name']);
				}
			}
			$item['image'] = pb_get_attachmenturl($item['picture']);
			$item = pb_lang_split_recursive($item);
			setvar("item",$item);	
		}else{
			flash("data_not_exists");
		}
		render("market/detail");		
	}
	
	function chart()
	{
		global $viewhelper;
		$this->loadModel("quote");
		if (isset($_GET['id'])) {
			$id = intval($_GET['id']);
		}
		if (empty($id)) {
			flash("pls_input_product_name", "index.php?do=market&action=quote");
		}
		$info = $this->quote->dbstuff->GetRow("SELECT * FROM ".$this->quote->table_prefix."quotes WHERE id=".$id);
		$this->quote->dbstuff->Execute("UPDATE ".$this->quote->table_prefix."quotes SET hits=hits+1 WHERE id=".$id);
		$info['pubdate'] = df($info['created']);
		$info['clicked'] = $info['hits'];
		$neighbour_info = $this->quote->getNeighbour($id, "id,title");
		if (!empty($neighbour_info['prev'])) {
			$title = $neighbour_info['prev']['title'];
			$info['prev_link'] = "<a href='index.php?do=market&action=chart&id=".$neighbour_info['prev']['id']."'>".$title."</a>";
			$info['prev_title'] = $title;
		}else{
			$info['prev_link'] = L("nothing", "tpl");
		}
		if (!empty($neighbour_info['next'])) {
			$title = $neighbour_info['next']['title'];
			$info['next_link'] = "<a href='index.php?do=market&action=chart&id=".$neighbour_info['next']['id']."'>".$title."</a>";
			$info['next_title'] = $title;
		}else{
			$info['next_link'] = L("nothing", "tpl");
		}
		$viewhelper->setTitle($info['title']);
		$viewhelper->setPosition($info['title']);
		setvar("item", pb_lang_split_recursive($info));
		$tpl_file = "detail.default";
		render($tpl_file, true);
	}
	
	function getChartData()
	{
		global $smarty;
		$this->loadModel("quote");
		if (!empty($_GET['type'])) {
			$type = trim($_GET['type']);
			if ($type=="price") {
				$info = $this->quote->dbstuff->GetRow("SELECT * FROM ".$this->quote->table_prefix."productprices WHERE id=".$id);
				$time_limit = strtotime("-1 month");
				if (!empty($info)) {
					$result = $this->quote->dbstuff->GetArray("SELECT FROM_UNIXTIME(created, '%m-%d') AS pubdate,price from ".$this->quote->table_prefix."productprices WHERE product_id=".intval($info['product_id'])." AND created>=".$time_limit);
					foreach ($result as $key=>$val) {
						$xy_data['x'][] = $val['pubdate'];
						$xy_data['y'][] = $val['price'];
					}
					setvar("title", pb_lang_split($info['title']));
					setvar("trend_x", $xy_data['x']);
					setvar("trend_y", $xy_data['y']);
					setvar("x_label", pb_lang_split($info['title']));
				}
			}
		}else{
			$info = $this->quote->dbstuff->GetRow("SELECT * FROM ".$this->quote->table_prefix."quotes WHERE id=".$id);
			if (!empty($info['trend_data'])) {
				$xy_data = unserialize($info['trend_data']);
				setvar("title", pb_lang_split($info['title']));
				setvar("trend_x", $xy_data['x']);
				setvar("trend_y", $xy_data['y']);
			}
		}
		header('Content-type: text/xml');
		echo pack("C3",0xef,0xbb,0xbf);
		$op = $smarty->fetch("charts/line.html", null, null, true);
		exit;
	}
}
?>