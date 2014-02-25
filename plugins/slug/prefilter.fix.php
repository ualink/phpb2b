<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
function smarty_prefilter_fix($tpl_source,&$smarty)
{
	$pattern = array(
		"/\<\!\-\-\\s*{(.+?)\}\s*\-\-\>/s",
		"/{(lang)+\s*(.+?)}/s",
		"/\{loop\s+(\S+)\s+(\S+)\}/",
		"/\{loop\s+(\S+)\s+(\S+)\s+(\S+)\}/",
		"/\{loop\s+(\S+)\s+(\S+)\s+(\S+)\s+(\S+)\}/",
		"/\{\/loop\}/",
		"/\{loopelse\}/",
		"/{pb\:(?!getdata)(.*?)\s/is",
		"/{\/pb\:(?!getdata)(.+?)}/is",
		"/\{\/pb\}/",
		"/<!--#.*-->/U"
		);
	$replace = array(
		"{\\1}",
		"{#\\2#}",
		"{foreach from=\\1 item=\\2}",
		"{foreach from=\\1 item=\\2 name=\\3}",
		"{foreach from=\\1 item=\\2 name=\\3 key=\\4}",
		"{/foreach}",
		"{foreachelse}",
		"{pb:getdata module='\\1' ",
		"{/pb:getdata}",
		"{/getdata}",
		""
		);
    $tpl_source = preg_replace($pattern, $replace, $tpl_source);
    $tpl_source = str_replace("pb:", "", $tpl_source);
    return $tpl_source;
}
?>