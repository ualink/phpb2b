<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
function smarty_function_category($params, &$smarty) {
	$conditions[] = 'available=1';
	$result = array();
	if (class_exists("Productcategories")) {
		$cat = new Productcategories();
		$cat_controller = new Productcategory;
	}else{
		uses("productcategory");
		$cat = new Productcategories();
		$cat_controller = new Productcategory;
	}
	$limit = $offset = 0;
	if (isset($params['row'])) {
		$limit = $params['row'];
	}
	if (isset($params['start'])) {
		$offset = $params['start'];
	}
	$cat->setCondition($conditions);
	$table_name = $cat->table_prefix.$cat_controller->pluralize($params['name']);
	$cat->dbstuff->setFetchMode(ADODB_FETCH_ASSOC);
	if (isset($params['depth'])) {
		//depth
		if ($params['depth']==-1) {
			$result = $cat->dbstuff->GetArray("SELECT * FROM ".$table_name.$cat->getCondition());
			return $result;
		}
	}
	$level = intval($params['depth']);
	$return = array();
	switch ($level) {
		case 1:
			$conditions[] = "level IN (".$level.")";
			$cat->setCondition($conditions);
			$return = $cat->dbstuff->GetArray("SELECT * FROM ".$table_name.$cat->getCondition()." ORDER BY display_order ASC");
			break;
		case 2:
			$conditions[] = "level IN (1,2)";
			$cat->setCondition($conditions);
			$result = $cat->dbstuff->GetArray("SELECT * FROM ".$table_name.$cat->getCondition()." ORDER BY display_order ASC");
			foreach ($result as $val) {
				if ($val['level']==1) {
					$result_1[$val['id']] = $val;
				}elseif ($val['level']==2){
					$result_2[] = $val;
				}
			}
			unset($result);
			foreach ($result_2 as $val2) {
				if ($val2['parent_id']==$result_1[$val2['parent_id']]['id']) {
					$result_1[$val2['parent_id']]['child'][] = $val2;
				}
			}
			$return = $result_1;
			unset($result, $result_1, $result_2);
			break;
		case 3:
			$conditions[] = "level IN (1,2,3)";
			$cat->setCondition($conditions);
			$result = $cat->dbstuff->GetArray("SELECT * FROM ".$table_name.$cat->getCondition()." ORDER BY display_order ASC");
			foreach ($result as $val) {
				if ($val['level']==1) {
					$return[$val['id']] = $val;
				}elseif ($val['level']==2){
					$return[$val['parent_id']]['child'][$val['id']] = $val;
				}elseif ($val['level']==3){
					$return[$val['top_parentid']]['child'][$val['parent_id']]['child'][] = $val;
				}
			}
			unset($result);
			break;
		default:
			break;
	}
	//$cat->setLimitOffset($offset, $limit);
	if (isset($params['output'])) {
		return $return;
	}else{
		if (empty($params['var'])) {
			$params['var'] = "category";
		}
		$smarty->assign($params['var'], pb_lang_split_recursive($return));
		unset($return);
	}
}
?>