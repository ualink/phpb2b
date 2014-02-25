<?php
class Adzones extends PbModel {
 	var $name = "Adzone";
 	
 	function __construct()
 	{
 		parent::__construct();
 	}
 	
 	function updateBreathe($id)
 	{
 		global $smarty;
 		$result = $this->read("*", $id);
 		if (!empty($result) && $result['style']==1) {
 			$tmp_arr = array();
 			$xml_template = DATA_PATH. "examples".DS."breathe.xml";
 			$cache_datafile = DATA_PATH."appcache/breathe-".$id.".xml";
 			$ad_result = $this->dbstuff->GetArray("SELECT * FROM ".$this->table_prefix."adses WHERE status='1' AND state='1' AND adzone_id=".$id." ORDER BY priority ASC");
 			if (!empty($ad_result)) {
 				for($i=0; $i<count($ad_result); $i++) {
 					$tmp_arr[$i]['link'] = (!empty($ad_result[$i]['target_url']))?$ad_result[$i]['target_url']:URL;
 					$tmp_arr[$i]['image'] = $ad_result[$i]['source_url'];
 				}
 			}
 			$data = $tmp_arr;
 			setvar("Items", $data);
 			$xml_data = $smarty->fetch("file:".$xml_template);
 			file_put_contents($cache_datafile, $xml_data);
 		}
 	}
}
?>