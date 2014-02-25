<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
function bread_compare($a, $b){
    if ($a['displayorder'] == $b['displayorder']) return 0;
    return ($a['displayorder'] < $b['displayorder']) ? -1 : 1;
}

function bread_array($breads, $seperate = " &gt; ")
{
	$bread = array();
    uasort($breads, "bread_compare");
    foreach ($breads as $key=>$val){
        if(!empty($val['url'])) {
            if(isset($val['params'])) $bread[] = "<a href='".$val['url'].queryString($val['params'])."' target='_self' title='".$val['title']."'>".$val['title']."</a>";
            else $bread[] = "<a href='".$val['url']."' target='_self' title='".$val['title']."'>".$val['title']."</a>";
        }else {
            $bread[] = $val['title'];
        }
    }
    return implode($seperate, $bread);
}

function queryString($q, $extra = array(), $escape = false) {
	if (empty($q) && empty($extra)) {
		return null;
	}
	$join = '&';
	if ($escape === true) {
		$join = '&amp;';
	}
	$out = '';

	if (is_array($q)) {
		$q = array_merge($extra, $q);
	} else {
		$out = $q;
		$q = $extra;
	}
	$out .= http_build_query($q, null, $join);
	if (isset($out[0]) && $out[0] != '?') {
		$out = '?' . $out;
	}
	return $out;
}
?>