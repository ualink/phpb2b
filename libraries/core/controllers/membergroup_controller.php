<?php
class Membergroup extends PbController {
	var $name = "Membergroup";
	
	function getUsergroups($type = 'all')
	{
		//system,special,define
		$return = array();
		$G['membergroup'] = cache_read("membergroup");
		$typeid = strval($type);
		foreach ($G['membergroup'] as $key=>$val) {
			if($typeid == 'all'){
				$return[$key] = $val['name'];
			}else{
				if ($typeid==$val['type']) {
					$return[$key] = $val['name'];
				}
			}
		}
		ksort($return);
		return $return;
	}
	
	function getExpireTime($live_time = null)
	{
		global $time_stamp;
		$return = null;
		$live_time = empty($live_time)?1:intval($live_time);
		switch ($live_time) {
			case 1:
				$return = $time_stamp+86400*30;
				break;
			case 2:
				$return = $time_stamp+86400*90;break;
			case 3:
				$return = $time_stamp+86400*180;break;
			case 4:
				$return = $time_stamp+86400*365;break;
			case 5:
				$return = $time_stamp+86400*365*5;break;
			default:
				$return = $time_stamp+86400*30;
				break;
		}
		return $return;
	}	
}
?>