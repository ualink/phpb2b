<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
function smarty_block_getdata($params, $content, Smarty_Internal_Template $sTemplate, &$repeat)
{ 
	$conditions = array();
	$mysql_limit = $mysql_orderby = $image_col = $_seperate = '';
	$target = "_blank";
	extract($params);
	if(!isset($params['assign']))
		$assign = 'row';
	if (!empty($params['name'])) {
		$assign = trim($params['name']);
	}
	$C = new PbController();
	$M = new PbModel();
	$M->condition = '';//Initial any last conditions
	$_table = $params['module'];
	switch ($params['module']) {
		case "offer":
		case "trade":
			$_table = "trade";
			break;
		case "fair":
		case "expo":
			$_table = "expo";
			break;
		case "announce":
			$_table = "announcement";
			break;
		case "ads":
			$_table = "adses";
			break;
		case "adword":
			$_table = "spread";
			break;
		case "price":
			$_table = "productprice";
			break;
		default:
			break;
	}
	if(method_exists($sTemplate,'get_template_vars'))
		$_bindex = $sTemplate->getTemplateVars('_bindex');
	else
		$_bindex = $sTemplate->getVariable('_bindex')->value;
	if(!$_bindex)
		$_bindex = array();
	if(isset($params['name']))
		if(!isset($_bindex[$params['name']]))
			$_bindex[$params['name']] = 1;
		else
			$_bindex[$params['name']] ++;
	$sTemplate->assign('_bindex',$_bindex);
	if(!isset($sTemplate->block_data))
		$sTemplate->block_data = array();	
	$iTags = count($sTemplate->_tag_stack);
	/**
	 * set condition
	 */
	$limit = $offset = 0;
	if (isset($params['row'])) {
		$limit = $params['row'];
	}
	if (isset($params['start'])) {
		$offset = $params['start'];
	}
	if (isset($_GET['pos'])) {
		$_pos = intval($_GET['pos']);
		$offset = ceil($_pos/$limit)*$row;	
	}
	if (!empty($params['flag'])) {
		$conditions[] = "flag='".$params['flag']."'";
	}
	if($_table=="industry" or $_table=="area"){
	if (isset($params['parentid'])) {
		if (!empty($params['parentid'])) {
			$conditions['parentid'] = "parent_id='".intval($params['parentid'])."' OR id=".intval($params['parentid']);
		}else{
			$conditions['parentid'] = "parent_id=0";
		}
	}else{
		$conditions['parentid'] = "parent_id=0";
	}
	}
	if (!empty($params['level'])) {
		if($_table == "newstype")
		$conditions[] = "level_id='".$params['level']."'";
		else
		$conditions[] = "level='".$params['level']."'";
	}
	if(isset($params['type'])) {
		$type = explode(",", $params['type']);
		$type = array_unique($type);
		foreach ($type as $val) {
			switch ($val) {
				case 'image':
					if ($module == "friendlink") {
						$image_col = "logo";
					}else{
						$image_col = "picture";
					}
					$conditions[] = "{$image_col}!=''";
					break;
				case 'hot':
					$orderbys[] = "hits DESC";
					break;
				case 'commend':
					$conditions[] = "if_commend='1'";
					break;
				default:
					break;
			}
		}
	}
	if (isset($params['exclude'])) $conditions[] = $M->getExcludeIds($params['exclude']);
	if (isset($params['include'])) $conditions[] = $M->getIncludeIds($params['include']);
	if(isset($row) && $row!='all'){
		$M->setLimitOffset($offset, $limit);
		$mysql_limit = $M->getLimitOffset();
	}
	if (!empty($params['limit'])) {
		$mysql_limit = " ".trim($params['limit']);
	}
	if (!empty($_GET['producttypeid'])) {
		$conditions[] = "type_id=".intval($_GET['producttypeid']);
	}
	if (!empty($params['companyid'])) {
		$conditions[] = "company_id=".intval($params['companyid']);
	}
	if (!empty($params['typeid'])) {
		$conditions[] = "type_id=".intval($params['typeid']);
	}
	if(empty($sTemplate->block_data[$iTags]))
	{
		# ************************************************************************
		# Main content
		$M->setCondition($conditions);
		$sql = sprintf("SELECT * FROM %s%s %s %s", $M->table_prefix,$C->pluralize($_table), $M->getCondition(),  $mysql_limit);
		$sTemplate->block_data[$iTags] = $M->GetArray($sql);
		//如果没有数据，那就不用再执行了(repeat)
		if(!$sTemplate->block_data[$iTags]) return $repeat = false;
		if(isset($stat)){
			$_total_count = $M->dbstuff->GetOne(sprintf("SELECT count(*) FROM %s%s %s", $M->table_prefix,$C->pluralize($_table), $M->getCondition()));
			$sTemplate->assign('total_count', $_total_count);
			$sTemplate->assign("paging", array('total'=>$_total_count));
		}
		# End main content
		# ************************************************************************
	}
	if(!$sTemplate->block_data[$iTags])
	{
		$repeat = false;
		return '';
	}
	if (!function_exists("smarty_function_the_url")) {
		require("function.the_url.php");
	}	
	$counts = count($sTemplate->block_data[$iTags]);
	if(list($key, $item) = each($sTemplate->block_data[$iTags]))
	{
		$_title = $_title_full = $_content = $_content_full = '';
		$item['rownum'] = $key;
		$item['iteration'] = ++$key;
		if (!empty($item['url'])) {
			$url = $item['url'];
		}else{
			$url = smarty_function_the_url(array("do"=>$module, "id"=>$item['id'], "action"=>'detail'));
		}
		if ($module == "company") {
			$url = smarty_function_the_url(array("id"=>$item['id'], "do"=>"company", "userid"=>$item['cache_spacename']));
		}elseif ($module == "tag"){
			$url = smarty_function_the_url(array("do"=>"product", "action"=>'lists', "q"=>$item['name']));
		}
		$item['url'] = $url;
		if (isset($item['title'])) {
			$_title = $item['name'] = $item['title'];
		}elseif (isset($item['name'])) {
			$_title = $item['title'] = $item['name'];
		}elseif (isset($item['subject'])){
			$_title = $item['title'] = $item['subject'];
		}elseif (isset($item['word'])){
			$_title = $item['title'] = $item['word'];
		}
		$_title_full = $_title;
		$_title = strip_tags(pb_lang_split($_title));
		$_title_full = strip_tags(pb_lang_split($_title_full));
		if (!empty($titlelen)) {
			$_title = mb_substr($_title, 0, $titlelen);
		}
		if (isset($item['description'])) {
			$_content =  $item['description'];
		}elseif (isset($item['content'])){
			$_content = $item['content'];
		}
		if (isset($item['clicked'])) {
			$item['hits'] = $item['clicked'];
		}
		$_content_full = $_content;
		if (!empty($_content) && isset($infolen)) {
			$_content = mb_substr($_content, 0, $infolen);
		}		
		if (isset($item['created'])) {
			$item['pubdate'] = df($item['created'], "m-d");
		}elseif (isset($item['submit_time'])) {
			$item['pubdate'] = df($item['submit_time']);
		}
		
		if (!empty($params['sep'])) {
			$_seperate= $params['sep'];
		}
		$item['content'] = $_content = strip_tags(pb_lang_split($_content));
//		if($seperate) $_title = ($key==$counts-1)?$_title:$_title.$seperate;	
		$item['link'] = '<a title="'.$_title_full.'" href="'.$url.'" target="'.$target.'">'.$_title.'</a>'.$_seperate;	
		if(!empty($item['picture'])) $item['thumb'] = $item['src'] = pb_get_attachmenturl($item['picture'], '', 'small');
		if (isset($item['highlight'])) {
			$item['style'] = parse_highlight($item['highlight']);
		}
		$sTemplate->assign($assign,$item);
		$repeat = true;
	}else{
		$repeat = false;
		reset($sTemplate->block_data[$iTags]);
		if(isset($params['name']))
		{
			unset($_bindex[$params['name']]);
			$sTemplate->assign('_bindex',$_bindex);
		}
	}
	if(!is_null($content)) print $content;
	if(!$repeat) $sTemplate->block_data[$iTags] = array();
}
?>