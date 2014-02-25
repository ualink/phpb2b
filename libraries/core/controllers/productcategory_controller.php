<?php
class Productcategory extends PbController {
	var $name = "Productcategory";
	function rewrite($id, $title = null)
	{
		$url = null;
		global $rewrite_able, $rewrite_compatible;
		if ($rewrite_able) {
			if ($rewrite_compatible && !empty($title)) {
				$url = "product/".rawurlencode($title)."/";
			}else{
				$url = "product/price/".$id.".html";
			}
		}else{
			if ($rewrite_compatible && !empty($title)) {
				$url = "product/price.php?title=".rawurlencode($title);
			}else{
				$url = "product/price.php?id=".$id;
			}
		}
		return $url;
	}
}
?>