<?php
class Tag extends PbController {
	var $name = "Tag";

	function rewrite($id)
	{
		$url = null;
		global $rewrite_able;
		if ($rewrite_able) {
			$url = "tag/".rawurlencode($title)."/";
		}else{
			$url = "index.php?do=offer&action=lists&q=".rawurlencode($title);
		}
		return $url;
	}	
}
?>