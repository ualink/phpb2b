<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
class Pages extends PbController {
	var $total_record;
	var $total_page;
	var $firstcount;
	var $displaypg = 9;
	var $current_page;
	var $pagenav;
	var $pagetpl_dir = '';
	var $pagetpl = "element.pages";//default
	var $_url;
	var $nextpage_link = "javascript:;";
	var $previouspage_link = "javascript:;";
	var $page_option = array(10,20,30);
	var $is_rewrite = false;
	
	function __construct() {
		$this->_url = pb_getenv('PHP_SELF');
	}
	
	function setPagenav($total_record)
	{
		global $smarty, $viewhelper;
		$params = $pagenav = null;
        if (isset($_REQUEST['page'])) {
        	if (!intval($_REQUEST['page'])) {
        		$page = 1;
        	}else {
        		$page = $_REQUEST['page'];
        	}
        }else{
        	$page = 1;
        }
		$this->total_record = $total_record;
		$this->current_page = $page;
		$lastpg = ceil($this->total_record / $this->displaypg);
		$this->total_page = $lastpg;
		$page = min($lastpg, $page);
        $firstcount = intval(($page-1) * $this->displaypg);
		if($firstcount<0) {
			$firstcount = 0;
		}
		$this->firstcount = $firstcount;
		if($lastpg<=1) {
			$this->pagenav = null;
			return;
		}
		if($page>$lastpg) $page = $lastpg;
		$get_params = array_filter($_GET);
		if ($total_record>0) {
			$get_params['total_record'] = $total_record;
		}
		if ($lastpg>0) {
			$get_params['total_pg'] = $lastpg;
		}
		//delete the same params
		//array_unique($get_params);
		$params = http_build_query($get_params);
		$params = urldecode($params);
		$params = str_replace(array("&page=$page","page=$page"), "[00]", $params);
//		$params = ereg_replace("(^|&)page=$page", "", $params);//2011.2.20
		if (!empty($params)) {
			$params = '?'.$params."&";
		}else{
			$params = '?';
		}
		if($page>1){
			$prev_begin = ($page-5)<=0?1:($page-5);
			$prev_end = ($page-1)<=0?1:($page-1);
			$prevs = range($prev_begin, $prev_end);
			$previous_page = $page-1;
			$this->previouspage_link = $this->_url."{$params}page={$previous_page}";
			if ($prev_begin>1) {
				$pagenav.="<a href='".$this->_url."{$params}page=1' title='".L('first_page', 'tpl')."'>1</a>... ";
			}
			foreach ($prevs as $val) {
				$pagenav.="<a href='".$this->_url."{$params}page={$val}'>$val</a>";
			}
		}
		$pagenav.="<span class='current'>{$page}</span>";
		if($page<$lastpg){
			$next_begin = ($page+1)>$lastpg?$lastpg:($page+1);
			$next_end = ($page+5)>$lastpg?$lastpg:($page+5);
			$nexts = range($next_begin, $next_end);
			$next_page = $page+1;
			$this->nextpage_link = $this->_url."{$params}page={$next_page}";
			foreach ($nexts as $val) {
				$pagenav.="<a href='".$this->_url."{$params}page={$val}'>{$val}</a>";
			}
			if($next_end<$lastpg) {
				$pagenav.="... <a href='".$this->_url."{$params}page={$lastpg}' title='".L('last_page', 'tpl')."'>{$lastpg}</a>";
			}
		}
		$current_record = $page*$this->displaypg;
		$start_record = $current_record - $this->displaypg;
		if ($start_record<1) {
			$start_record = 1;
		}
		if ($current_record>$total_record) {
			$current_record = $total_record;
		}
		$tpl_file = $this->pagetpl.$smarty->tpl_ext;
		$smarty->assign("pages", $pagenav);
		$smarty->assign("start", $start_record);
		$smarty->assign("end", $current_record);
		$smarty->assign("count", $total_record);
//		$smarty->assign("prev", $this->previouspage_link);
		$smarty->assign("middle", $pagenav);
//		$smarty->assign("next", $this->nextpage_link);
		if (!empty($this->pagetpl_dir)) {
			$tpl_file = $this->pagetpl_dir.DS.$this->pagetpl.$smarty->tpl_ext;
		}
		if (!$viewhelper->tpl_exists($tpl_file)) {
			$tpl_file = 'pages'.DS.$this->pagetpl.$smarty->tpl_ext;
		}
		$cache_id = null;
		if ($smarty->caching) {
			$cache_id = $_GET['page']."|".$_GET['id'];
		}
		$this->pagenav = $smarty->fetch($tpl_file, $cache_id);
	}
	
	function getPagenav()
	{
		return $this->pagenav;
	}
}
?>