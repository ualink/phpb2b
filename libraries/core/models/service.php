<?php
class Services extends PbModel {
 	var $name = "Service";
 	var $validate = array(
	'content' => array( 'required' => true),
	'email' => array( 'required' => true)
	);
 	
 	function __construct()
 	{
 		$this->validate['content']['message'] = L("content_cant_be_empty");
 		$this->validate['email']['message'] = L("please_input_email");
		parent::__construct();
 	}
 	
 	function formatResult($result, $types = null)
 	{
 		global $_PB_CACHE;
 		if (!empty($types)) {
 			$service_types = $types;
 		}else{
 			$service_types = $_PB_CACHE['service_type'];
 		}
 		if (!empty($result)) {
 			for($i=0; $i<count($result); $i++){
 				$result[$i]['typename'] = $service_types[$result[$i]['type_id']];
 				$result[$i]['submitdate'] = date("Y-m-d H:i", $result[$i]['created']);
// 				$result[$i]['title'] = pb_lang_split($result[$i]['title']);
 				if (!empty($result[$i]['revert_date'])) {
 					$result[$i]['revertdate'] = date("Y-m-d H:i", $result[$i]['revert_date']);
 				}
 			}
 		}
 		return $result;
 	}
 }