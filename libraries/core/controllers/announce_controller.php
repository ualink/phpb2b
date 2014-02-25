<?php
//instead of announcement_controller at 2012.9.11
class Announce extends PbController {
	var $name = "Announce";
 	public static $instance = NULL;
	
	function __construct()
	{
		$this->loadModel("announcement");
	}
	
	function getInstance() {
		if (!$instance) {
			$instance[0] = new Announce();
		}
		return $instance[0];
	}
	
	function detail()
	{
		global $viewhelper;
		$viewhelper->setTitle(L("announce", "tpl"));
		$types = cache_read("type");
		$viewhelper->setPosition('test', "index.php?do=announce");
		if (isset($_GET['title'])) {
			$title = trim($_GET['title']);
			$res = $this->announcement->findBySubject($title);
			$id = $res['id'];
		}
		if (isset($_GET['id'])) {
			$id = intval($_GET['id']);
		}
		if(!empty($id)){
			$result = $this->announcement->findById($id);
			if (!empty($result)) {
				$result['message'] = nl2br($result['message']);
				$viewhelper->setPosition($types['announcementtype'][$result['announcetype_id']], "index.php?do=announce&typeid=".$result['announcetype_id']);
				$viewhelper->setTitle($result['subject']);
				$viewhelper->setPosition($result['subject']);
				$viewhelper->setMetaDescription($result['message']);
				$result['title'] = pb_lang_split($result['subject']);
				$result['content'] = pb_lang_split($result['message']);
				$result['typename'] = pb_lang_split($types['announcementtype'][$result['announcetype_id']]);
				$result['pubdate'] = df($result['created']);
				$neighbour_info = $this->announcement->getNeighbour($id, "id,subject AS title,subject");
				if (!empty($neighbour_info['prev'])) {
					$title = pb_lang_split($neighbour_info['prev']['title']);
					$result['prev_link'] = "<a href='".$this->url(array("id"=>$neighbour_info['prev']['id'], "module"=>'announce'))."'>".$title."</a>";
					$result['prev_title'] = $title;
				}else{
					$result['prev_link'] = L("nothing", "tpl");
				}
				if (!empty($neighbour_info['next'])) {
					$title = pb_lang_split($neighbour_info['next']['title']);
					$result['next_link'] = "<a href='".$this->url(array("id"=>$neighbour_info['next']['id'], "module"=>'announce'))."'>".$title."</a>";
					$result['next_title'] = $title;
				}else{
					$result['next_link'] = L("nothing", "tpl");
				}
				setvar("item", $result);
				setvar("PageTitle", strip_tags($result['subject']));
				render("detail.default", true);
			}
		}
	}
	
	function index()
	{
		global $viewhelper;
		$conditions = array();
		$viewhelper->setTitle(L("announce", "tpl"));
		$types = cache_read("type");
		$viewhelper->setPosition(L("announce", "tpl"), "index.php?do=announce");
		if (!empty($_GET['typeid'])) {
			$conditions[] = "announcetype_id=".intval($_GET['typeid']);
		}
		$result = $this->announcement->findAll("*", null, $conditions, "display_order ASC,id DESC");
		if (!empty($result)) {
			for($i=0; $i<count($result); $i++){
				if (!empty($result[$i]['created'])) {
					$result[$i]['pubdate'] = date("Y-m-d", $result[$i]['created']);
					$result[$i]['title'] = pb_lang_split($result[$i]['subject']);
					$result[$i]['typename'] = $types['announcementtype'][$result[$i]['announcetype_id']];
					$result[$i]['type_id'] = $result[$i]['announcetype_id'];
				}
			}
			setvar("module", "announce");
			setvar("items", $result);
		}
		render("list.default", true);
	}
}
?>