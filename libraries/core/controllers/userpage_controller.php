<?php
class Userpage extends PbController {
	var $name = "Userpage";
	
	function rewrite($url = null, $id = 0, $name = null)
	{
		global $rewrite_able, $rewrite_compatible;
		$return = null;
		if (!empty($url)) {
			$return = $url;
		}else{
			if ($rewrite_able && $rewrite_compatible && !empty($name)) {
				$return = "page/".rawurlencode($name)."/";
			}else{
				$return = "page.php?name=".$name;
			}
		}
	}
}