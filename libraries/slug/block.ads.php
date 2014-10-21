<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
function smarty_block_ads($params, $content, &$smarty, &$repeat){
	$field = "ads";
	if (isset($params['name']))
	{
		$field = $params['name'];
	}
	if (!class_exists("Adses")) {
		uses("ad");
		$ad = new Adses();
		$ad_controller = new Ad();
	}else{
		$ad = new Adses();
		$ad_controller = new Ad();
	}
	$md5 = md5($field); 
	$max_width = $max_height = 0;
	if (empty($content))     
	{                  
		$conditions[] = "status='1' AND state='1'";
		if(isset($params['id'])){
			$result = $ad->read("*", intval($params['id']));
		}else{
			if (isset($params['type'])) {
				$repeat = false;
				echo $ad->getFocus($params);
				return;
			}
			if (isset($params['typeid'])) {
				$typeid = intval($params['typeid']);
				$conditions[] = "adzone_id=".$typeid;
				$zone_res = $ad->GetRow("select * from {$ad->table_prefix}adzones adz where id=".$typeid);
				if (isset($params['groupid'])) {
					if (!empty($zone_res['membergroup_ids'])) {
						$membergroup_ids = explode(",", $zone_res['membergroup_ids']);
						if (!in_array($params['groupid'], $membergroup_ids)) {
							return;
						}
					}
				}
				if ($zone_res['what']==2) {
					echo stripslashes($zone_res['additional_adwords']);
					return;
				}
				if (isset($zone_res['style']) && $zone_res['style'] == 1) {
					//flash roll
					$repeat = false;
					if(!empty($params['width'])) $zone_res['width'] = $params['width'];
					if(!empty($params['height'])) $zone_res['height'] = $params['height'];
					echo $ad->getBreathe($zone_res);
					return;
				}
				$adzone_name = $zone_res['name'];
				$adzone_id = $zone_res['id'];
				$max_width = intval($zone_res['width']);
				$max_height = intval($zone_res['height']);
				$max_ad = intval($zone_res['max_ad']);
				unset($zone_res);
			}
			if (!empty($params['exclude'])) $conditions[] = $ad->getExcludeIds($params['exclude']);
			if (!empty($params['include'])) $conditions[] = $ad->getIncludeIds($params['include']);
			if (!empty($params['keyword'])) {
				$conditions[] = "title like '%".$params['keyword']."%'";
			}
			$orderby = null;
			$limit = $offset = 0;
			if (isset($params['row'])) {
				$limit = $params['row'];
			}elseif ($max_ad){
				$limit = $max_ad;
			}
			if (isset($params['start'])) {
				$offset = $params['start'];
			}
			$ad->setLimitOffset($offset, $limit);
			if (isset($params['orderby'])) {
				$orderby = " ORDER BY ".trim($params['orderby']);
			}else{
				$orderby = " ORDER BY priority ASC";
			}
			$ad->setCondition($conditions);
			$sql = "SELECT * FROM {$ad->table_prefix}adses ".$ad->getCondition()."{$orderby}".$ad->getLimitOffset();
			$result = $ad->GetArray($sql);
		}
		if (count($result) == 0)         
		{             
			$result = false;         
		}
		$GLOBALS['__SMARTY_VARS'][$md5] = $result;     
	}     
	if (is_array($GLOBALS['__SMARTY_VARS'][$md5]))     
	{         
		$vars = array_shift($GLOBALS['__SMARTY_VARS'][$md5]);
		foreach ($vars as $key=>$item) {
			$vars['rownum'] = $key;
			$url = $vars['target_url'];
			$vars['url'] = $url;
			if (!empty($vars['end_date']) && $vars['end_date']<$ad->timestamp) {
					if (!empty($vars['picture_replace'])) {
						$vars['source_url'] = $vars['picture_replace'];
						$vars['title'] = L("ads_on_sale");
					}
			}
			$vars['src'] = $vars['thumb'] = $ad->getCode($vars, $max_width, $max_height);
			if ($vars['is_image']) {
				$vars['link'] = '<a title="'.$vars['title'].'" href="'.$url.'" rel="promotion" linkf="ads">'.$vars['src'].'</a>';
			}elseif($vars['source_type'] == "application/x-shockwave-flash"){
				$vars['link'] = $vars['src'];
			}else{
				$vars['link'] = '<a title="'.$vars['title'].'" href="'.$url.'" linkf="ads">'.$vars['title'].'</a>';
			}		
		}	
		$smarty->assign($field, $vars);
		if (count($GLOBALS['__SMARTY_VARS'][$md5]) == 0)         
		{             
			$GLOBALS['__SMARTY_VARS'][$md5] = false;
		}
		$repeat = true;     
	} else {         
		$repeat = false;     
	}     
	return $content;//or echo $content
}
?>