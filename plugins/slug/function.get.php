<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2214 $
 */
function smarty_function_get($params, &$smarty)
{
	$op = null;
	extract($params);
	global $tb_prefix, $pdb;
	if (empty($var)) {
		$var = "item";
	}
	if (!empty($from)) {
		switch ($from) {
			case "market":
				$num = 4;
				if (isset($params['row'])) {
					$num = intval($params['row']);
				}
				$latest_commend_markets = $industry->GetArray("SELECT * FROM ".$tb_prefix."markets WHERE if_commend='1' AND status='1' AND picture!='' ORDER BY id DESC LIMIT ".$num);
				$urls = $infos = $images = array();
				if (!empty($latest_commend_markets)) {
					while (list($key, $val) = each($latest_commend_markets)) {
						$urls[] = $industry->getPermaLink($val['id'], null, 'market');
						$infos[] = pb_lang_split($val['name']);
						$images[] = pb_get_attachmenturl($val['picture'], '', $size);
					}
					$items['url'] = implode("|", $urls);
					$items['info'] = implode("|", $infos);
					$items['image'] = implode("|", $images);
					$return = $items;
				}
				break;
			case "area":
				if (class_exists("Areas")) {
					$area = new Areas();
				}else{
					uses("area");
					$area = new Areas();
				}
				$return = $area->getLevelAreas();
				break;
			case "industry":
				//depth
				if (class_exists("Industries")) {
					$industry = new Industries();
					$obj_controller = new Industry();
				}else{
					uses("industry");
					$industry = new Industries();
					$obj_controller = new Industry();
				}
				$return = $industry->getCacheIndustry();
				break;
			case "type":
				if(!empty($name)){
					//depth
					if (class_exists("Industries")) {
						$industry = new Industries();
						$obj_controller = new PbController();
					}else{
						uses("industry");
						$industry = new Industries();
						$obj_controller = new PbController();
					}
					$name = $obj_controller->pluralize($name);
					$industry->findIt($name);
					$return = $industry->params['data'][1];
					if (isset($multi)) {
						$return = $obj_controller->flatten_array($return);
					}
					if (empty($var)) {
						$var = "Items";
					}
				}
				break;
			default:
				$return = cache_read($name, $key);
				break;
		}
	}
	if (!empty($sql)) {
		//replace table prefix
		$pdb->setFetchMode(ADODB_FETCH_ASSOC);
		$sql = str_replace("pb_", $tb_prefix, $sql);
		//for secure
		if (eregi('insert|update|delete|union|into|load_file|outfile|replace', $sql)) {
			trigger_error('no supported sql.');
		}
		//mysql_escape_string()
		$return = $industry->GetArray($sql);
	}
	if (isset($name)){
		switch ($name) {
			case "language":
				global $G;
				$languages = unserialize($G['setting']['languages']);
				if (!empty($languages)) {
					if (!isset($echo)) {
						$smarty->assign($var, $languages);
					}else{
						foreach ($languages as $lang_key=>$lang_val) {
							$tmp="<a href='".URL.'redirect.php?url='.pb_getenv("REQUEST_URI")."&app_lang=".$lang_key."' title='".$lang_val['title']."'>";
							if($image && !empty($lang_val['img'])){
								$tmp.="<img src='".$lang_val['img']."' alt='".$lang_val['title']."' />";
							}else{
								$tmp.=$lang_val['title'];
							}
							$tmp.="</a>";
							if ($sep) {
								$tmp.=$sep;
							}
							if (isset($title_li) && $title_li=="list") {
								$op.="<li>".$tmp."</li>";
							}else{
								$op.=$tmp;
							}
						}
					}
				}
				break;
			case "nav":
				$_nav = cache_read("nav");
				$navs = $_nav['navs'];
				if (!empty($exclude)) {
					$_exclude_navs = explode(",", $exclude);
					foreach ($_exclude_navs as $_exkey=>$_exval) {
						unset($navs[$_exval]);
					}
				}
				if(empty($echo))
				$smarty->assign($var, $navs);
				else{
					foreach ($navs as $nav) {
						$op.= '<li id="mn_'.$nav['id'].'" class="nav_item nav-item-'.$nav['id'];
						$file_name = pb_getenv('REQUEST_URI');
						if (strpos($file_name, $nav['url'])!==false && $nav['url']!='index.php') {
							$op.=' current_nav_item';
						}
						$op.='"><a href="'.$nav['url'].'" target="_self"><span>'.pb_lang_split($nav['name']).'</span></a></li>';
					}
				}
				break;
			default:
				if (is_file(CACHE_COMMON_PATH."cache_".$name.".php")) {
					require(CACHE_COMMON_PATH."cache_".$name.".php");
				}
				if(isset($_PB_CACHE)) $smarty->assign($var, $_PB_CACHE);
				break;
		}
	}	
	if (!empty($return)) {
		$smarty->assign($var, $return);
	}
	return $op;
}
?>