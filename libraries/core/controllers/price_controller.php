<?php
class Price extends PbController {
	var $name = "Price";
	
	function index()
	{
		$tpl_file = "price/index";
	}
	
	function lists()
	{
		$tpl_file = "price/list";
	}
	
	function detail()
	{
		$tpl_file = "detail.default";
	}
}
?>