<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2214 $
 */
function smarty_block_industry($params, $content, &$smarty, &$repeat) {
	$conditions = array();
	$param_count = count($smarty->_tag_stack);
	if(empty($params['name'])) $params['name'] = "industry";
	if (class_exists("Industries")) {
		$industry = new Industries();
		$industry_controller = new Industry();
	}else{
		uses("industry");
		$industry = new Industries();
		$industry_controller = new Industry();
	}
	$conditions[] = "available=1";
	if (isset($params['depth'])) {
		//depth
		if ($params['depth']==-1) {
			if (!empty($_GET['level']) && !isset($_GET['areaid'])) {
				$conditions['level'] = "level=".intval($_GET['level']);
			}else{
				$conditions['level'] = "level=1";
			}
		}
	}
	if(!empty($params['typeid'])) {
		$conditions[] = "indusrytype_id=".$params['typeid'];
	}
	if (!empty($params['id'])) {
		$conditions[] = "id=".$params['id'];
	}
	if (!empty($params['topid'])) {
		$conditions[] = "top_parentid='".$params['topid']."'";
	}
	if (!empty($params['level'])) {
		$conditions['level'] = "level=".$params['level'];
	}
	if (isset($params['parentid'])) {
		if (!empty($params['parentid'])) {
			$conditions[] = "parent_id='".$params['parentid']."' OR id=".intval($params['parentid']);
		}else{
			$conditions['parentid'] = "parent_id=0";
		}
		
	}elseif (isset($_GET['parentid'])){
		$i_id = intval($_GET['parentid']);
		$conditions[] = "parent_id='".$i_id."' OR id=".$i_id;
	}
	if (!empty($params['topparentid'])) {
		$conditions[] = "top_parentid='".$params['topparentid']."'";
	}
	if (!empty($params['exclude'])) {
		$conditions[] = "id NOT IN (".$params['exclude'].")";
	}
	if (!empty($params['include'])) {
		$conditions[] = "id IN (".$params['include'].")";
	}
	$orderby = null;
	if (isset($params['orderby'])) {
		$orderby = " ORDER BY ".trim($params['orderby'])." ";
	}else{
		$orderby = " ORDER BY id DESC";
	}
	$industry->setCondition($conditions);
	$limit = $offset = 0;
	if (isset($params['row'])) {
		$limit = $params['row'];
	}
	if (isset($params['start'])) {
		$offset = $params['start'];
	}
	if (!empty($limit)) {
		$industry->setLimitOffset($offset, $limit);
	}else{
		$industry->limit_offset = 0;
	}
	$sql = "SELECT id,name,level,name as title,alias_name,highlight,url,parent_id FROM {$industry->table_prefix}industries i ".$industry->getCondition()."{$orderby}".$industry->getLimitOffset();
	$industry->dbstuff->setFetchMode(ADODB_FETCH_ASSOC);
	if(empty($smarty->blockvars[$param_count])) {
		$smarty->blockvars[$param_count] = $industry->GetArray($sql);
		if(!$smarty->blockvars[$param_count]) return $repeat = false;
	}
	if (!function_exists("smarty_function_the_url")) {
		require("function.the_url.php");
	}
	if(list($key, $item) = each($smarty->blockvars[$param_count])) {
		$repeat = true;
		if (!empty($item['url'])) {
			$url = $item['url'];
		}else{
			$url = smarty_function_the_url(array("module"=>"special", "type"=>"industry", "id"=>$item['id'], "do"=>$smarty->_tpl_vars['do']));
		}
		$item['url'] = $url;
		$item['name'] = pb_lang_split($item['name']);
		$item['title'] = pb_lang_split($item['title']);
		if (isset($params['titlelen'])) {
	    	$item['title'] = mb_substr(strip_tags($item['title']), 0, $params['titlelen']);
		}
		$item['style'] = parse_highlight($item['highlight']);
		$item['link'] = '<a title="'.$item['name'].'" href="'.$url.'">'.$item['title'].'</a>';
		if (isset($_GET['industryid'])) {
			$id = intval($_GET['industryid']);
			if ($id>0 && $id==$item['id']) {
				$item['child'] = $industry->GetArray("SELECT *,name AS title FROM {$industry->table_prefix}industries WHERE parent_id=".$id." AND available=1 ORDER BY display_order ASC");
			}
		}
		$smarty->assign($params['name'], $item);
	}
	else {
		$repeat = false;
		reset($smarty->blockvars[$param_count]);
	}
	if(!is_null($content)) print $content;
	if(!$repeat) $smarty->blockvars[$param_count] = array();
}
?>