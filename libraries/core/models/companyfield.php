<?php
class Companyfields extends PbModel {
 	var $name = "Companyfield";
 	public static $instance = NULL;

 	function __construct()
 	{
 		parent::__construct();
 	}

	function getInstance() {
		if (!isset(self::$instance[get_class()])) {
			self::$instance = new Companyfields();
		}
		return self::$instance;
	}
}
?>