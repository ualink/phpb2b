<?php
/**
 * instead by announce_controller
 * @2012.9.11
 */
class Announcement extends PbController {
	var $name = "Announcement";
 	public static $instance = NULL;
	
	function getInstance() {
		if (!$instance) {
			$instance[0] = new Announcement();
		}
		return $instance[0];
	}
}
?>