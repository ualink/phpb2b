<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2053 $
 */
function smarty_function_the_attachment($params){
	$return = '';
	extract($params);
	if (!empty($name)) {
		$return = pb_get_attachmenturl($name, '', $type);
		$return = '<img src="'.$return.'" alt=""/>';
	}
	if (!empty($url)) {
		$return = $url;
	}
	return $return;
}
?>