<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2218 $
 */
class PbView extends PbObject
{
	public $admin_dir = 'pb-admin';
	public $office_dir = 'virtual-office';
	public $homepage_file_name = "index.php";
	public $titles = array();
	public $pageTitle = null;
	public $url_container = null;
	public $webroot;
	public $here = null;
	public $position = array();
	public $addParams;
	public $metaKeyword;
	public $metaDescription;
	public $caching = false;
	public $colorarray = array('#000000', '#FF0000', '#FFA500', '#FFFF00', '#008000', '#00FFFF', '#0000FF', '#800080', '#808080');
	protected $engine;

	public function __construct($options = array()){
		global $G;
		if (!empty($_REQUEST['page'])) {
			$this->addParams = "&page=".intval($_REQUEST['page']);
		}
		$this->setPosition($G['setting']['site_name'], URL."index.php");
	}
	
	//echo 'Your val is $name,and is not exsit in this class!';
	public function __get($property) {
		return $this->$property;
	}
	
	//echo 'Your val is '.$name.'=>'.$value;
	public function __set($property, $value) {
		$this->$property = $value;
	}	
	
	function setMetaDescription($meta_description)
	{
		$this->metaDescription = mb_substr(pb_strip_spaces(strip_tags(pb_lang_split($meta_description))), 0, 100);
	}
	
	function setMetaKeyword($meta_keyword)
	{
		$this->metaKeyword = strip_tags(str_replace(array(" "), ",", pb_lang_split($meta_keyword)));
	}	

    function setTitle($title, $image = 0)
    {
    	$this->titles[] = pb_lang_split($title).($image?"[".L('have_picture', 'tpl')."]":null);
    }

    function getTitle($seperate = ' - ', $auto_site_title = true)
    {
        $pageTitle = null;
    	krsort($this->titles);
    	$pageTitle = implode($seperate, $this->titles);
    	if (strpos($pageTitle, $seperate) == 0) {
    		;
    	}
    	if ($auto_site_title) {
    		$pageTitle.=$seperate.$GLOBALS['G']['setting']['site_title'];
    	}
    	$this->pageTitle = $pageTitle;
    	return $pageTitle;
    }
    
	function bread_compare($a, $b){
	    if ($a['displayorder'] == $b['displayorder']) return 0;
	    return ($a['displayorder'] < $b['displayorder']) ? -1 : 1;
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

    function setPosition($title, $url = null, $displayorder = 0, $additonalParams = array())
    {
        $this->position[] = array('displayorder'=>$displayorder, 'title'=>pb_lang_split($title), 'url'=>$url, 'params'=>$additonalParams);
    }

    function getPosition($seperate = ' &raquo; ', $show_last = false)
    {
    	$position = array();
    	$current_position = null;
    	$positions = $this->position;
        if (!empty($positions)) {
	        foreach ($positions as $key=>$val){
	            if(!empty($val['url'])) {
	                if(isset($val['params'])) $position[] = "<a href='".$val['url'].$this->queryString($val['params'])."' target='_self' title='".$val['title']."'>".$val['title']."</a>";
	                else $position[] = "<a href='".$val['url']."' target='_self' title='".$val['title']."'>".$val['title']."</a>";
	            }else {
	                $position[] = $val['title'];
	            }
	        }
	        $heres = implode($seperate, $position);
	        $current_position = "<em>".L("your_current_position", "tpl")."</em>".$heres;
	        $this->here = $current_position;
	        if ($show_last) {
	        	$this->here.=$seperate;
	        }
        }
        if (empty($this->metaDescription)) {
        	$this->metaDescription = strip_tags(end($position));
        }
        return $this->here;
    }
	
	function redirect($url, $type=301)
	{
		if ($type == 301) header("HTTP/1.1 301 Moved Permanently");
		header("Location: $url");
		echo 'This page has moved to <a href="'.$url.'">'.$url.'</a>';
		exit();
	}
	
	function tpl_exists($file)
	{
		global $smarty;
		return $smarty->templateExists($file);
	}
}
?>