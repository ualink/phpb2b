<?php
class Home extends PbController {
	var $name = "Home";
	
	function __construct()
	{
		global $topleveldomain_support, $pdb, $tb_prefix, $G;
		if ($topleveldomain_support) {
			$host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : $_SERVER['HTTP_HOST']);
			$result = $pdb->GetRow("SELECT id,cache_spacename FROM {$tb_prefix}companies WHERE topleveldomain='".$host."' AND status='1'");
			if (!empty($result)) {
				header("HTTP/1.1 301 Moved Permanently");
				header("location:".$host);
				header("Connection: close");
				exit();
			}
		}
		$redirect_url = $G['setting']['redirect_url'];
		if (!empty($redirect_url)) {
			if(isset($_SERVER['REQUEST_URI']) && !strstr($_SERVER['REQUEST_URI'], ".php")){;
				$url = $redirect_url;
				header("HTTP/1.1 301 Moved Permanently");
				header("Location:$url");
				exit;
			}
		}
	}
	
	function index()
	{
		render("index");
	}
}
?>