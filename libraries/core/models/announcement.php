<?php
class Announcements extends PbModel {
 	var $name = "Announcement";
 	var $amount;
 	
 	function __construct()
 	{
		parent::__construct();
 	}
 	
 	function initSearch()
 	{
 		if (isset($_GET['typeid'])) {
 			$this->condition[] = "announcetype_id='".intval($_GET['typeid'])."'";
 		}
 		if (isset($_GET['q'])) {
 			$this->condition[] = "subject like '%".htmlspecialchars($_GET['q'], ENT_QUOTES)."%'";
 		}
 		$this->amount = $this->findCount();
 	}
	
 	function Search($firstcount, $displaypg)
 	{
 		global $cache_types;
 		$result = $this->findAll("*", null, null, "display_order ASC,id DESC", $firstcount, $displaypg);
 		while(list($keys,$values) = each($result)){
 			$result[$keys]['title'] = $values['subject'];
 			$result[$keys]['digest'] = $values['message'];
 			$result[$keys]['pubdate'] = df( $values['created']);
 			$result[$keys]['typename'] = $cache_types['announcementtype'][$values['announcetype_id']];
 			$result[$keys]['url'] = $this->url(array("do"=>"announce", "id"=>$values['id']));
 			unset($result[$keys]['subject'], $result[$keys]['message']);
 		}
 		return $result;
 	}
}
?>