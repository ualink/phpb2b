<?php
class Membertype extends PbController {
	var $name = "Membertype";
 	
	function __construct()
	{
		$this->loadModel("membertype");
	}
}
?>