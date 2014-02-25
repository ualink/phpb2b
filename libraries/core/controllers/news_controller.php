<?php
class News extends PbController {
	var $name = "News";
	
	function __construct()
	{
		$this->loadModel("news");
		$this->loadModel("newstype");
	}
	
	function lists()
	{
		global $pos;
		$this->news->initSearch();
		$result = $this->news->Search($pos, $this->displaypg);
		setvar("items", $result);
		setvar("module", strtolower($this->name));
		render("list.default");
	}
	
	function index()
	{
		global $viewhelper;
		require(CACHE_COMMON_PATH."cache_type.php");
		$title = L("industry_info", "tpl");
		//cal
		require(CLASS_PATH. "calendar.class.php");
		$cal = new Calendar;
		$cal->setMonthNames(explode(",", L("month_unit", "tpl")));
		$cal->setDayNames(explode(",", L("week_unit", "tpl")));
		$d = getdate(time());
		$day = $_GET['day'];
		if ($day=="") {
			$day = $d['mday'];
		}
		$month = $_GET['month'];
		if ($month == "")
		{
			$month = $d["mon"];
		}
		$year = $_GET['year'];
		if ($year == "")
		{
			$year = $d["year"];
		}
		if(isset($_GET['year']) && isset($_GET['month']) && isset($_GET['day'])){
			$title.=L("journal", "tpl", $year.$month.$day);
			setvar("date_line", $year."-".$month."-".$day);
		}
		$viewhelper->setTitle($title);
		setvar("Calendar", $cal->getMonthView($month, $year));
		$cache_id = $year.$month.$day;
		//end cal
		render("news/index");
	}
	
	function detail()
	{
		global $viewhelper;
		using("tag","meta");
		$tag = new Tags();
		$meta = new Metas();
		$conditions = array();
		$viewhelper->setTitle(L("info", "tpl"));
		$viewhelper->setPosition(L("info", "tpl"), "index.php?do=news");
		if (isset($_GET['title'])) {
			$title = trim($_GET['title']);
			$res = $this->news->findByTitle($title);
			$id = $res['id'];
		}
		if (isset($_GET['id'])) {
			$id = intval($_GET['id']);
		}
		if (!empty($id)) {
			$_PB_CACHE['newstype'] = cache_read("type", "newstype");
			$this->news->clicked($id);
			$info = $this->news->read("*",$id);
			if (empty($info) or !$info) {
				flash("data_not_exists", '', 0);
			}
			if(!empty($info['tag_ids'])){
				$the_tags = $tag->getTagsByIds($info['tag_ids'], true);
				$tmp = null;
				$info['tag'] = $tag->tag;
				foreach ($the_tags as $key=>$val) {
					$tmp.="<a href='".$this->url(array("module"=>"search", "do"=>"news", "q"=>urlencode($val)))."'>".$val."</a> ";
				}
				$info['tag_link'] = $tmp;
			}
			if (!empty($info['picture'])) {
				$info['image'] = pb_get_attachmenturl($info['picture'], '', 'small');
			}
			$info['pubdate'] = df($info['created']);
			$info['typename'] = $_PB_CACHE['newstype'][$info['type_id']];
			$viewhelper->setTitle($info['typename']);
			$viewhelper->setPosition($info['typename'], $this->url(array("module"=>"search", "do"=>"news", "typeid"=>$info['type_id'])));
			//seo info
			$meta_info = $meta->getSEOById($id, 'news', false);
			empty($meta_info['title'])?$viewhelper->setTitle($info['title']):$viewhelper->setTitle($meta_info['title']);
			empty($meta_info['description'])?$viewhelper->setMetaDescription($info['content']):$viewhelper->setMetaDescription($meta_info['description']);
			if(isset($meta_info['keyword'])) $viewhelper->setMetaKeyword($meta_info['keyword']);
			$viewhelper->setPosition($info['title']);
			if (!empty($info['require_membertype'])) {
				$require_ids = explode(",", $info['require_membertype']);
				if (!empty($pb_userinfo['pb_userid'])) {
					$membertype_id = $this->news->dbstuff->GetOne("SELECT membertype_id FROM {$tb_prefix}members WHERE id='".$pb_user['pb_userid']."'");
					if (!in_array($membertype_id, $require_ids)) {
						$info['content'] = L("news_membertype_not_allowed", "tpl");
					}
				}else{
					$info['content'] = L("news_membertype_not_allowed", "tpl");
				}
			}
			if ($info['type'] == 1) {
				$info['source'] = L("company_news", "tpl");
				$info['content'] = "<a href='".$info['content']."'>".$info['content']."</a>";
			}
			if (!empty($info['picture'])) {
				$info['image_url'] = pb_get_attachmenturl($info['picture']);
			}
			$neighbour_info = $this->news->getNeighbour($id, "id,title");
			if (!empty($neighbour_info['prev'])) {
				$title = pb_lang_split($neighbour_info['prev']['title']);
				$info['prev_link'] = "<a href='".$this->url(array("module"=>"news", "id"=>$neighbour_info['prev']['id']))."'>".$title."</a>";
				$info['prev_title'] = $title;
			}else{
				$info['prev_link'] = L("nothing", "tpl");
			}
			if (!empty($neighbour_info['next'])) {
				$title = pb_lang_split($neighbour_info['next']['title']);
				$info['next_link'] = "<a href='".$this->url(array("module"=>"news", "id"=>$neighbour_info['next']['id']))."'>".$title."</a>";
				$info['next_title'] = $title;
			}else{
				$info['next_link'] = L("nothing", "tpl");
			}
			setvar("item",pb_lang_split_recursive($info));
		}else{
			flash();
		}
		setvar("Newstypes",$_PB_CACHE['newstype']);
		render("detail.default");
	}
}
?>