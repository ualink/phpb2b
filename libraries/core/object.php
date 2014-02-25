<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
class PbObject{
	
	public $params;
	public $fontFace = 'incite.ttf';

	public function __construct() {
		global $viewhelper, $smarty, $app_lang, $pdb, $tb_prefix, $time_stamp;
		$args = func_get_args();
		$this->view = $smarty;
		$this->lang = $app_lang;
		$this->dbstuff = $pdb;
		$this->timestamp = $time_stamp;
		$this->table_prefix = $tb_prefix;
		$this->view_helper = $viewhelper;
	}
	
	public function toString() {
		$class = get_class($this);
		return $class;
	}
	
}
?>