<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
class Strings extends PbObject
{
	var $name;
	var $string;
	
	function __construct()
	{
		
	}
	
	function txt2array($data)
	{
		$datas = explode("\r\n", $data);
		$tmp_str = array();
		if (!empty($datas)) {
			foreach ($datas as $val) {
				$tmp_str[] = $val;
			}
			return $tmp_str;
		}else{
			return false;
		}
	}
	
	function txt2file($data)
	{
		$datas = trim(preg_replace("/(\s*(\r\n|\n\r|\n|\r)\s*)/", "\r\n", $data));
		return $datas;
	}
}
?>