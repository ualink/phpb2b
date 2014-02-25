<?php
class Setting extends PbController {
	var $name = "Setting";
	
	function __construct()
	{
		$this->loadModel("setting");
	}

	function test(){
		echo "this is a model";
	}
}
?>