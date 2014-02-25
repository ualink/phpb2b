<?php
class Standards extends PbModel {
 	var $name = "Standard";
 	var $amount;

 	function __construct()
 	{
 		parent::__construct();
 	}
 	
 	function initSearch()
 	{
 		if (isset($_GET['typeid'])) {
 			$typeid = intval($_GET['typeid']);
 			$this->condition[] = "type_id='".$typeid."'";
 		}
 		if (isset($_GET['filter'])) {
 			$filter = intval($_GET['filter']);
 			$this->condition[] = "created>".($this->timestamp-$filter);
 		}
 		if (isset($_GET['q'])) {
 			$searchkeywords = $_GET['q'];
 			$this->condition[] = "title like '%".$searchkeywords."%'";
 		}
 		$this->amount = $this->findCount();
 	}
 	
 	function Search($firstcount, $displaypg)
 	{
 		global $cache_types;
 		$result = $this->findAll("*,content AS digest", null, null, "id DESC", $firstcount, $displaypg);
 		while(list($keys,$values) = each($result)){
 			$result[$keys]['pubdate'] = df($values['created']);
 			$result[$keys]['force_date'] = df($values['force_time']);
 			$result[$keys]['typename'] = $cache_types['standardtype'][$values['type_id']];
 			$result[$keys]['url'] = $this->url(array("do"=>"standard", "id"=>$values['id']));
 		}
 		return $result;
 	}
 	
 	function downloadtxt($info)
 	{
 		$title = pb_lang_split($info['title']);
		header("Content-type:   application/octet-stream");
		header("Accept-Ranges:   bytes");
		Header("Accept-Length: ".filesize($file_name));
		header("Content-Disposition:   attachment;   filename=".rawurlencode($title).".txt");
		header("Expires:   0");
		header("Cache-Control:   must-revalidate,   post-check=0,   pre-check=0");
		header("Pragma:   public");
		echo $title."\r\n".pb_lang_split($info['content']);
		exit();	
 	}
}
?>