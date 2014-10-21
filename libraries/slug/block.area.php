<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2193 $
 */
function smarty_block_area($params, $content, &$smarty, &$repeat) {
	$conditions = array();
	$param_count = count($smarty->_tag_stack);
	if(empty($params['name'])) $params['name'] = "area";
	if (class_exists("Areas")) {
		$area = new Areas();
		$area_controller = new Area();
	}else{
		uses("area");
		$area = new Areas();
		$area_controller = new Area();
	}
	$conditions[] = "available=1";
	if(isset($params['typeid'])) {
		$conditions[] = "areatype_id=".$params['typeid'];
	}
	if (isset($params['depth'])) {
		//depth
		if ($params['depth']==-1) {
			if (!empty($_GET['level'])) {
				if (isset($_GET['areaid'])) {
					$next_level = $area->dbstuff->GetOne("SELECT level FROM ".$area->table_prefix."areas WHERE id=".intval($_GET['areaid']));
					$next_level+=1;
					$conditions['level'] = "level=".$next_level;
					$conditions['parentid'] = "parent_id=".intval($_GET['areaid'])." OR id=".intval($_GET['areaid']);
				}elseif(!isset($_GET['industryid'])){
					$conditions['level'] = "level=".intval($_GET['level']);
				}else{
					$conditions['level'] = "level=1";
				}
			}else{
				$conditions['level'] = "level=1";
			}
		}
	}
	if(!empty($params['typeid'])) {
		$conditions[] = "areatype_id=".$params['typeid'];
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
			$conditions['parentid'] = "parent_id='".$params['parentid']."' OR id=".$params['parentid'];
		}else{
			$conditions['parentid'] = "parent_id=0";
		}
		
	}
	if (!empty($params['exclude'])) {
		$conditions[] = "id NOT IN (".$params['exclude'].")";
	}
	if (!empty($params['include'])) {
		$conditions[] = "id IN (".$params['include'].")";
	}
	$area->setCondition($conditions);
	$orderby = null;
	if (isset($params['orderby'])) {
		$orderby = " ORDER BY ".trim($params['orderby'])." ";
	}else{
		$orderby = " ORDER BY display_order ASC,id DESC";
	}
	$limit = $offset = 0;
	if (isset($params['row'])) {
		$limit = $params['row'];
	}
	if (isset($params['start'])) {
		$offset = $params['start'];
	}
	if (!empty($limit)) {
		$area->setLimitOffset($offset, $limit);
	}else{
		$area->limit_offset = 0;
	}
	$sql = "SELECT id,name,level,name as title,alias_name,highlight,url,parent_id FROM {$area->table_prefix}areas a ".$area->getCondition()."{$orderby}".$area->getLimitOffset().";";
	$area->dbstuff->setFetchMode(ADODB_FETCH_ASSOC);
	if(empty($smarty->blockvars[$param_count])) {
		$smarty->blockvars[$param_count] = $area->GetArray($sql);
		if(!$smarty->blockvars[$param_count]) return $repeat = false;
	}
	if (!function_exists("smarty_function_the_url")) {
		require("function.the_url.php");
	}
	if(list($key, $item) = each($smarty->blockvars[$param_count])) {
		$repeat = true;
		$item['rownum'] = $key;
		$item['iteration'] = ++$key;
		if (!empty($item['url'])) {
			$url = $item['url'];
		}else{
			$url = smarty_function_the_url(array("module"=>"special", "type"=>"area", "id"=>$item['id'], "do"=>$smarty->_tpl_vars['do']));
		}
		$item['url'] = $url;
		$item['style'] = parse_highlight($item['highlight']);
		$item['title'] = pb_lang_split($item['title']);
		$item['name'] = pb_lang_split($item['name']);
		if (isset($params['titlelen'])) {
	    	$item['title'] = mb_substr(strip_tags($item['title']), 0, $params['titlelen']);
		}
		$item['link'] = '<a title="'.$item['name'].'" href="'.$url.'">'.$item['title'].'</a>';
		if (isset($_GET['areaid'])) {
			$id = intval($_GET['areaid']);
			if ($id>0 && $id==$item['id']) {
				$item['child'] = $area->GetArray("SELECT *,name AS title FROM {$area->table_prefix}areas WHERE parent_id=".$id." AND available=1 ORDER BY display_order ASC;");
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