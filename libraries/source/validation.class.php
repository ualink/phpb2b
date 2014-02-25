<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
define('VALID_NOT_EMPTY', '/.+/');
class Validation extends PbObject {
	var $regex = null;
	var $subject = null;
	var $pattern = array();
	var $errors = array();
	var $error = false;
	
	static function getInstance(){
		static $instance;

		if(!isset($instance)){
			$c = __CLASS__;
			$instance = new $c;
		}

		return $instance;
	}

	function url($inputUrl){
		$regUrl = "^(http://)?((localhost)|(([0-9a-z][0-9a-z_-]+.){1,3}[a-z]{2,4}))$";
		$resultUrl = ereg($regUrl,$inputUrl);
		if ($resultUrl == 1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function ip($minIpAddress, $maxIpAddress) {
		global $_SERVER;
		$onlineip = empty($_SERVER['REMOTE_ADDR']) ? pb_getenv('REMOTE_ADDR') : $_SERVER['REMOTE_ADDR'];
		$longip = ip2long($onlineip);
		if($this->range($longip, $minIpAddress, $maxIpAddress)) {
			die("IP FOBIDDEN!");
		}
	}
	
	function range($x, $min, $max) {
		return $x >= $min && $x <= $max;
	}
	
	function notEmpty($subject) {
		$_this = Validation::getInstance();
		$_this->_reset();
		$_this->subject = $subject;

		if (is_array($subject)) {
			$_this->_extract($subject);
		}

		if (empty($_this->subject) && $_this->subject != '0') {
			return false;
		}
		$_this->regex = '/[^\s]+/m';
		return $_this->_check();
	}
	
	function _extract($params) {
		$_this = Validation::getInstance();
		extract($params, EXTR_OVERWRITE);

		if (isset($subject)) {
			$_this->subject = $subject;
		}
		if (isset($regex)) {
			$_this->regex = $regex;
		}
	}
	
	function _check() {
		$_this = Validation::getInstance();
		if (preg_match($_this->regex, $_this->subject)) {
			$_this->error[] = false;
			return true;
		} else {
			$_this->error[] = true;
			return false;
		}
	}
	
	function _reset() {
		$this->subject = null;
		$this->regex = null;
		$this->error = false;
		$this->errors = array();
	}
	
	function extension($check, $extensions = array('gif', 'jpeg', 'png', 'jpg')) {
		if (is_array($check)) {
			return Validation::extension(array_shift($check), $extensions);
		}
		$extension = strtolower(array_pop(explode('.', $check)));
		foreach ($extensions as $value) {
			if ($extension == strtolower($value)) {
				return true;
			}
		}
		return false;
	}
	
	function minLength($check, $min) {
		$length = strlen($check);
		return ($length >= $min);
	}
	
	function maxLength($check, $max) {
		$length = strlen($check);
		return ($length <= $max);
	}
	
	function inRange($check, $lower = null, $upper = null) {
		if (!is_numeric($check)) {
			return false;
		}
		if (isset($lower) && isset($upper)) {
			return ($check > $lower && $check < $upper);
		}
		return is_finite($check);
	}
	
	function show(&$model)
	{
		$return = '';
		if (!empty($model->validationErrors)) {
			$return = '<div class="message messageFailure">';
			foreach ($model->validationErrors as $key=>$val) {
				$return.="<p>".$val."</p>";
			}
			$return.= '</div>';
		}
		return $return;
	}
}