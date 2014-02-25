<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
class Caches extends PbObject {
	var $cache_name = null;
	var $lang_dirname = null;
	var $cache_path = null;
	
	function __construct($cache_name = null)
	{
//		if (!empty($this->lang_dirname)) {
//			$this->cache_path = PHPB2B_ROOT."data".DS."cache".DS.$this->lang_dirname.DS;
//		}else{
			$this->cache_path = CACHE_PATH;
//		}
		if (!empty($cache_name)) {
			$this->cache_name = $cache_name;
		}
		parent::__construct();
		if (!is_dir($this->cache_path)) {
			mkdir($this->cache_path, 0777, true);
		}
	}
	
	function updateSettings() {
		global $G;
		if(isset($G['setting']) && is_array($G['setting'])) {
			writeCache('setting', '', '$_PB_CACHE[\'setting\'] = '.evalArray($G['setting']).";\n\n");
		}
	} 	
	
	function updateIndexCache()
	{
		$this->dbstuff->setFetchMode(ADODB_FETCH_ASSOC);
		$letters = range('a', 'z');
		$return = array();
		foreach ($letters as $val) {
			$tmp_arr = $this->dbstuff->GetArray("SELECT id,name,picture FROM ".$this->table_prefix."brands WHERE letter='".$val."' ORDER BY id DESC LIMIT 0,7");
			if (!empty($tmp_arr)) {
				$return[$val] = $tmp_arr;
			}
		}
		$data = "\$_PB_CACHE['brand'] = ".$this->evalArray($return);
		return $this->writeCache("brand", null, $data);
	}
	
	function updateTypes($cache_name = '', $extra_data = '')
	{
		$datas = $tmp_arr = array();
		$cache_data = null;
		$cache_types = array("announcementtype", "areatype", "companytype", "expotype", "friendlinktype", "industrytype", "markettype", "membertype", "newstype", "tradetype", "productsort", "companynewstype", "albumtype","standardtype","dicttype","helptype","markettype","goodtype");
		foreach ($cache_types as $table) {
			$extra_col = ",name";
			if ($table=="helptype") {
				$extra_col = ",title AS name";
			}
			$tmp_arr = $this->dbstuff->GetArray("SELECT id".$extra_col." FROM ".$this->table_prefix.$table."s");
			if (!empty($tmp_arr)) {
				foreach ($tmp_arr as $key=>$val) {
					$datas[$val['id']] = $val['name'];
				}
				if ($table=="tradetype") {
					$table = "offertype";
				}
				$cache_data.= "\$_PB_CACHE['".$table."'] = ".$this->evalArray($datas).";\n";
				unset($datas, $tmp_arr);
			}
		}
		$cache_data.= "\$_PB_CACHE = array_map_recursive('pb_lang_split', \$_PB_CACHE);\n\n";
		return $this->writeCache('type', '', $cache_data);
	}
	
	function updateTypevars()
	{
		$cur_data = $type_js_data = null;
		$sql = "SELECT id,type_name FROM {$this->table_prefix}typemodels tm";
		$Typemodels = $this->dbstuff->GetArray($sql);
		$type_js_data = "<!--// Created ".date("M j, Y, G:i")." \n";
		if (!empty($Typemodels)) {
			foreach ($Typemodels as $key=>$val) {
				$data = array();
				$tmp_js_option = null;
				$tmp_options = $this->dbstuff->GetArray("SELECT option_value,option_label FROM {$this->table_prefix}typeoptions WHERE typemodel_id=".$val['id']." ORDER BY id ASC");
				$type_js_data.="var ".$val['type_name']." = [";
				if (!empty($tmp_options)) {
					foreach ($tmp_options as $option_key=>$option_val) {
						$tmp_js_option[] = "['".$option_val['option_label']."','".$option_val['option_value']."']";
						$data[$option_val['option_value']] = $option_val['option_label'];
					}
				}
				$tmp_js = implode(",", $tmp_js_option);
				$type_js_data.=$tmp_js."];\n";
				$cachename = $val['type_name'];
				$cur_data.= "\$_PB_CACHE['$cachename'] = ".$this->evalArray($data).";\n";
			}
			$cur_data.= "\$_PB_CACHE = array_map_recursive('pb_lang_split', \$_PB_CACHE);\n\n";
			$this->writeCache('typeoption', '', $cur_data);
			$type_js_data.="//-->";
			if (!class_exists("JSMinException")) {
				include_once(CLASS_PATH. "jsmin.class.php");
			}
			file_put_contents($this->cache_path. "type.js", $type_js_data);
			$jsMin = new JSMin(file_get_contents($this->cache_path. "type.js"), false);
			$out = $jsMin->minify();
			return file_put_contents($this->cache_path. "type.js", $out);
		}
		$false = false;
		return $false;
	}
	
	function evalArray($array, $level = 0) {
		if(!is_array($array)) {
			return "'".$array."'";
		}
		if(is_array($array) && function_exists('var_export')) {
			return var_export($array, true);
		}
	
		$space = '';
		for($i = 0; $i <= $level; $i++) {
			$space .= "\t";
		}
		$evaluate = "Array\n$space(\n";
		$comma = $space;
		if(is_array($array)) {
			foreach($array as $key => $val) {
				$key = is_string($key) ? '\''.addcslashes($key, '\'\\').'\'' : $key;
				$val = !is_array($val) && (!preg_match("/^\-?[1-9]\d*$/", $val) || strlen($val) > 12) ? '\''.addcslashes($val, '\'\\').'\'' : $val;
				if(is_array($val)) {
					$evaluate .= "$comma$key => ".evalArray($val, $level + 1);
				} else {
					$evaluate .= "$comma$key => $val";
				}
				$comma = ",\n$space";
			}
		}
		$evaluate .= "\n$space)";
		return $evaluate;
	}
	
	function writeCache($script, $cachenames = '', $cachedata = '', $prefix = 'cache_') {
		global $phpb2b_auth_key;
		$fpc = true;
//		if (!empty($this->lang_dirname)) {
//			$app_lang = $this->lang_dirname;
//		}
		//after 4.3, this is not mustable.
//		else {
//			$app_lang = $this->lang_dirname = $GLOBALS['app_lang'];
//		}
		$mod_label = "Created";
		if (!empty($cachenames)) {
			if(is_array($cachenames) && !$cachedata) {
				foreach($cachenames as $name) {
					$cachedata .= $this->getCacheArray($name, $script);
				}
			}else{
				$cachedata.= $this->getCacheArray($cachenames);
			}
		}
		if(!empty($cachedata)){
			if(!empty($this->lang_dirname))
			$dir = PHPB2B_ROOT.'data'.DS.'cache'.DS.$this->lang_dirname.DS;
			else
			$dir = PHPB2B_ROOT.'data'.DS.'cache'.DS;
			if(!is_dir($dir)) {
				pb_create_folder($dir);
			}
			$file_name = $dir.$prefix.$script.".php";
			if(file_exists($file_name)) {
				$mod_label = "Modified";
			}
			$fpc = file_put_contents($file_name, "<?php\n/**\n * PHPB2B cache file, DO NOT change me!".
			"\n * {$mod_label}: ".date("M j, Y, G:i").
			"\n * Id: ".md5($prefix.$script.'.php'.$cachedata.$phpb2b_auth_key)."\n */\n\n$cachedata\n?>");
		}
		if(!$fpc) {
			exit(L("write_file_error_and_retry"));
		}else{
			return true;
		}
		
	}	
	
	function getCacheArray($cachename = '', $script = '') {
		$conditions = $curdata = '';
		$data = array();
		if (empty($cachename) && !empty($this->cache_name)) {
			$cachename = $this->cache_name;
		}
		switch($cachename) {
			case 'nav':
				$this->lang_dirname = '';
				$navs = $this->dbstuff->GetArray("SELECT id,name,description,url,target,display_order,highlight FROM {$this->table_prefix}navs  WHERE status=1 ORDER BY display_order ASC");
				$navmns = $_nlink = array();
				if (!empty($navs)) {
					foreach ($navs as $nav=>$nav_val) {
						$lang_title = $nav_val['name'];
						$_tmp = pb_lang_split($lang_title, true);
						foreach ($_tmp as $_nk=>$_nv) {
							$_nlink[$_nk] = '<a href="'.$nav_val['url'].'" title="'.$_nv.'" '.parse_highlight($nav_val['highlight']).'><span>'.$_nv.'</span></a>';
						}
						$navmns[$nav_val['id']]['link'] = pb_lang_merge($_nlink);
						$navmns[$nav_val['id']]['id'] = $nav_val['id'];
						$navmns[$nav_val['id']]['name'] = $lang_title;
						$navmns[$nav_val['id']]['url'] = $nav_val['url'];
						$navmns[$nav_val['id']]['level'] = $nav_val['display_order'];
					}
					$data['navs'] = $navmns;
				}
				$curdata = "\$_PB_CACHE['$cachename'] = ".$this->evalArray($data).";\n\n";
				break;
			case 'trusttype':
				$this->lang_dirname = '';
				$this->dbstuff->setFetchMode(ADODB_FETCH_ASSOC);
				$conditions = "";
				$sql = "SELECT * FROM {$this->table_prefix}trusttypes ORDER BY display_order ASC,id DESC";
				$result = $this->dbstuff->GetArray($sql);
				foreach ($result as $key=>$val) {
					$result[$key]['avatar'] = $val['image'];
					unset($result[$key]['description'], $result[$key]['display_order'], $result[$key]['status'], $result[$key]['image']);
					$data[$val['id']] = $result[$key];
				}
				$curdata = "\$_PB_CACHE['$cachename'] = ".$this->evalArray($data).";\n\n";
				break;
			case 'country':
				$this->lang_dirname = '';
				$this->dbstuff->setFetchMode(ADODB_FETCH_ASSOC);
				$conditions = "";
				$sql = "SELECT * FROM {$this->table_prefix}countries ORDER BY display_order ASC,id ASC";
				$result = $this->dbstuff->GetArray($sql);
				foreach ($result as $key=>$val) {
					$result[$key]['image'] = $val['picture'];
					unset($result[$key]['display_order']);
					$data[$val['id']] = $result[$key];
				}
				$curdata = "\$_PB_CACHE['$cachename'] = ".$this->evalArray(pb_lang_split_recursive($data)).";\n\n";
				break;
			case 'setting':
				$this->lang_dirname = '';
				$tmp_mail = array();
				$table = 'setting';
				$conditions = "";
				$sql = "SELECT * FROM {$this->table_prefix}settings WHERE type_id IN (0,1)";
				$setting = $this->dbstuff->GetArray($sql);
				foreach ($setting as $key=>$val) {
					//For multi
					$s_title = $val['valued'];
					$data[$val['variable']] = $s_title;
				}
				//set sendmail
				$tmp_mail['send_mail'] = $data['send_mail'];
				$tmp_mail['auth_protocol'] = $data['auth_protocol'];
				$tmp_mail['smtp_server'] = $data['smtp_server'];
				$tmp_mail['smtp_port'] = $data['smtp_port'];
				$tmp_mail['smtp_auth'] = $data['smtp_auth'];
				$tmp_mail['mail_from'] = $data['mail_from'];
				$tmp_mail['mail_fromwho'] = $data['mail_fromwho'];
				$tmp_mail['auth_username'] = $data['auth_username'];
				$tmp_mail['auth_password'] = $data['auth_password'];
				$tmp_mail['mail_delimiter'] = $data['mail_delimiter'];
				$tmp_mail['sendmail_silent'] = $data['sendmail_silent'];
				$data['mail'] = serialize($tmp_mail);
				unset($tmp_mail,$data['send_mail'],$data['smtp_server'],$data['smtp_port'],$data['smtp_auth'],$data['mail_from'],$data['mail_fromwho'],$data['auth_username'],$data['auth_password'],$data['mail_delimiter'],$data['sendmail_silent']);
				$data['capt_auth'] = bindec($data['capt_logging'].$data['capt_register'].$data['capt_post_free'].$data['capt_add_market'].$data['capt_login_admin'].$data['capt_apply_friendlink'].$data['capt_service']);
				unset($data['capt_logging'],$data['capt_register'],$data['capt_post_free'],$data['capt_add_market'],$data['capt_login_admin'],$data['capt_apply_friendlink'],$data['capt_service']);
				$curdata = "\$_PB_CACHE['$cachename'] = ".$this->evalArray($data).";\n\n";
			break;
			case 'area':
				$this->lang_dirname = '';
				$sql = "select * from {$this->table_prefix}areas a where a.parent_id=0 ORDER by display_order asc";
				$top_areas = $sec_areas = $third_areas = $areas = $total_areas = array();
				$area1 = $this->dbstuff->GetArray($sql);
				$op = "<!--// Created ".date("M j, Y, G:i")." -->\n";
				$op .= "var data_area = { \n";
				foreach($area1 as $key=>$val){
					//For multi
					$i_title = $val['name'];
//					$tmp = unserialize($val['description']);
//					if(!empty($tmp[$this->lang_dirname])) $i_title = $tmp[$this->lang_dirname];
					$top_areas[$val['id']] = $total_areas[1][$val['id']] = $i_title;
					$sql = "select * from {$this->table_prefix}areas a where level=2 AND parent_id=".$val['id']." ORDER by display_order asc";
					$sec_areas = $this->dbstuff->GetArray($sql);
					foreach($sec_areas as $key2=>$val2){
						//For multi
						$i_title = $val2['name'];
//						$tmp = unserialize($val2['description']);
//						if(!empty($tmp[$this->lang_dirname])) $i_title = $tmp[$this->lang_dirname];
						$third_areas = $this->dbstuff->GetArray("select id,name,parent_id,top_parentid from {$this->table_prefix}areas a where level=3 AND parent_id=".$val2['id']." ORDER by display_order asc");
						$areas[$val['id']]['sub'][$val2['id']] = $i_title;
						$total_areas[2][$val2['id']] = $i_title;
						foreach($third_areas as $key3=>$val3){
							//For multi
							$i_title = $val3['name'];
//							$tmp = unserialize($val3['description']);
//							if(!empty($tmp[$this->lang_dirname])) $i_title = $tmp[$this->lang_dirname];
							$areas[$val2['id']]['sub'][$val3['id']] = $total_areas[3][$val3['id']] = $i_title;
						}
					}
				}
				$top_areas = pb_lang_split_recursive($this->convert2utf8($top_areas));
				$areas = pb_lang_split_recursive($this->convert2utf8($areas));
				$op .= "'0':".json_encode($top_areas);
				$tmp_op = array();
				foreach ($top_areas as $js_key=>$js_val){
					if(isset($areas[$js_key])){
						foreach ($areas[$js_key] as $js_key1=>$js_val1) {
							$tmp_op[] = "'0,{$js_key}':".json_encode($areas[$js_key]['sub']);
							foreach ($areas[$js_key]['sub'] as $js_key2=>$js_val2) {
								if(!empty($areas[$js_key2]['sub'])) $tmp_op[] = "'0,{$js_key},{$js_key2}':".json_encode($areas[$js_key2]['sub']);
							}
						}
					}
				}
				if (!empty($tmp_op)) {
					$op .=",\n";
					$tmp_op = implode(",\n", $tmp_op);
					$op .= $tmp_op."\n}";
				}else{
					$op .= "\n}";
				}
				$fp = file_put_contents($this->cache_path. "area.js", $op);
				ksort($total_areas);
				$curdata = "\$_PB_CACHE['$cachename'] = ".$this->evalArray($total_areas).";\n\n";
				//db cache.
				$data = array();
				$op = "<?php\n";
				$op.="return ";
				$tmp_level_top = $this->dbstuff->GetArray("SELECT id,name,parent_id,level,url FROM ".$this->table_prefix."areas WHERE available=1 ORDER BY display_order ASC");
				$data = pb_format_tree($tmp_level_top, 0);	
				$op.=$this->evalArray($data);
				unset($data);
				$op.="\n";
				$op.="?>";
				$fp = file_put_contents(CACHE_COMMON_PATH. "area.php", $op);
			break;
			case 'industry':
				$this->lang_dirname = '';
				if (!function_exists("smarty_function_the_url")) {
					require(SLUGIN_PATH."function.the_url.php");
				}
				$sql = "SELECT name,id,name AS title,description FROM {$this->table_prefix}industries i WHERE i.parent_id=0 and available=1 ORDER BY display_order ASC";
				$top_levels = $sec_levels = $third_levels = $datas = $total_datas = array();
				$level1 = $this->dbstuff->GetArray($sql);
				$op = "<!--// Created ".date("M j, Y, G:i")." -->\n";
				$op .= "var data_industry = { \n";
				foreach($level1 as $key=>$val){
					//For multi
					$i_title = $val['name'];
//					$tmp = unserialize($val['description']);
//					if(!empty($tmp[$this->lang_dirname])) $i_title = $tmp[$this->lang_dirname];
					$top_levels[$val['id']] = $total_datas[1][$val['id']] = $i_title;
					$sql = "SELECT id,name,parent_id,top_parentid,name AS title,description FROM {$this->table_prefix}industries t WHERE available=1 AND level=2 AND parent_id=".$val['id']." ORDER BY display_order ASC";
					$sec_levels = $this->dbstuff->GetArray($sql);
					foreach($sec_levels as $key2=>$val2){
						//For multi
						$i_title = $val2['name'];
//						$tmp = unserialize($val2['description']);
//						if(!empty($tmp[$this->lang_dirname])) $i_title = $tmp[$this->lang_dirname];
						$third_levels = $this->dbstuff->GetArray("SELECT id,name,parent_id,top_parentid,name AS title,description FROM {$this->table_prefix}industries t WHERE available=1 AND level=3 AND parent_id=".$val2['id']." ORDER BY display_order ASC");
						$datas[$val['id']]['sub'][$val2['id']] = $i_title;
						$total_datas[2][$val2['id']] = $i_title;
						foreach($third_levels as $key3=>$val3){
							//For multi
							$i_title = $val3['name'];
//							$tmp = unserialize($val3['description']);
//							if(!empty($tmp[$this->lang_dirname])) $i_title = $tmp[$this->lang_dirname];
							$datas[$val2['id']]['sub'][$val3['id']] = $total_datas[3][$val3['id']] = $i_title;
						}
					}
				}
				$top_levels = pb_lang_split_recursive($this->convert2utf8($top_levels));
				$datas = pb_lang_split_recursive($this->convert2utf8($datas));
				$op .= "'0':".json_encode($top_levels);
				$tmp_op = array();
				foreach ($top_levels as $js_key=>$js_val){
					if(isset($datas[$js_key])){
						foreach ($datas[$js_key] as $js_key1=>$js_val1) {
							$tmp_op[] = "'0,{$js_key}':".json_encode($datas[$js_key]['sub']);
							foreach ($datas[$js_key]['sub'] as $js_key2=>$js_val2) {
								if(!empty($datas[$js_key2]['sub'])) $tmp_op[] = "'0,{$js_key},{$js_key2}':".json_encode($datas[$js_key2]['sub']);
							}
						}
					}
				}
				if (!empty($tmp_op)) {
					$op .=",\n";
					$tmp_op = implode(",\n", $tmp_op);
					$op .= $tmp_op."\n}";
				}else{
					$op .= "\n}";
				}
				$fp = file_put_contents($this->cache_path. "industry.js", $op);
				unset($op);
				ksort($total_datas);
				$curdata = "\$_PB_CACHE['$cachename'] = ".$this->evalArray($total_datas).";\n\n";
				unset($top_levels,$sec_levels,$third_levels,$datas,$total_datas,$op);
				//db cache.
				$data = array();
				$op = "<?php\n";
				$op.="return ";
				$tmp_level_top = $this->dbstuff->GetArray("SELECT id,name,parent_id,level,url FROM ".$this->table_prefix."industries WHERE available=1 ORDER BY display_order ASC");
				$data = pb_format_tree($tmp_level_top, 0);	
				$op.=$this->evalArray($data);
				unset($data);
				$op.="\n";
				$op.="?>";
				$fp = file_put_contents(CACHE_COMMON_PATH. "industry.php", $op);
			break;
			case 'userpage':
				$this->lang_dirname = '';
				$sql = "SELECT id,name,title,url,digest FROM {$this->table_prefix}userpages ORDER BY display_order ASC,id ASC";
				$result = $this->dbstuff->GetArray($sql);
				if (!empty($result)) {
					$i=0;
					foreach ($result as $key=>$val) {
						$data[$i]['id'] = $val['id'];
						$data[$i]['title'] = $val['title'];
						$data[$i]['name'] = $val['name'];
						$data[$i]['digest'] = $val['digest'];
						if (!empty($val['url'])) {
							$data[$i]['url'] = $val['url'];
						}else{
							$data[$i]['url'] = "";
						}
						$i++;
					}
				}
				$curdata = "\$_PB_CACHE['$cachename'] = ".$this->evalArray($data).";\n\n";
			break;
			case 'form':
				$this->lang_dirname = '';
				$form_result = $this->dbstuff->GetArray("SELECT * FROM {$this->table_prefix}forms ORDER BY id ASC");
				if (!empty($form_result)) {
					foreach ($form_result as $val) {
						$item_result = $this->dbstuff->GetArray("SELECT * FROM {$this->table_prefix}formitems WHERE id IN (".$val['items'].") ORDER BY id ASC");
						if (!empty($item_result)) {
							foreach ($item_result as $val1) {
								$data[$val['id']][$val1['id']]['id'] = $val1['identifier'];
								$data[$val['id']][$val1['id']]['label'] = $val1['title'];
							}
						}
					}
					$curdata = "\$_PB_CACHE['$cachename'] = ".$this->evalArray($data).";\n\n";
				}
			break;
			case 'membergroup':		
				$this->lang_dirname = '';		
				$sql = "SELECT * FROM {$this->table_prefix}membergroups mg ORDER BY mg.id DESC";
				$membergroup_result = $this->dbstuff->GetArray($sql);
				if (!empty($membergroup_result)) {
					foreach ($membergroup_result as $key=>$val) {
						$data[$val['id']]['name'] = $val['name'];
						$data[$val['id']]['max_offer'] = $val['max_offer'];
						$data[$val['id']]['type_id'] = $val['membertype_id'];
						$data[$val['id']]['max_product'] = $val['max_product'];
						$data[$val['id']]['max_job'] = $val['max_job'];
						$data[$val['id']]['max_companynews'] = $val['max_companynews'];
						$data[$val['id']]['max_market'] = $val['max_companynews'];
						$data[$val['id']]['max_album'] = $val['max_companynews'];
						$data[$val['id']]['max_producttype'] = $val['max_producttype'];
						$data[$val['id']]['max_attach_size'] = $val['max_attach_size'];
						$data[$val['id']]['max_size_perday'] = $val['max_size_perday'];
						$data[$val['id']]['max_favorite'] = $val['max_favorite'];
						$data[$val['id']]['type'] = $val['type'];
						$data[$val['id']]['avatar'] = $val['picture'];
						$data[$val['id']]['allow_space'] = $val['allow_space'];
						$tmp_allow = sprintf("%02b", $val['allow_offer']);
						$data[$val['id']]['offer_allow'] = intval($tmp_allow[0]);
						$data[$val['id']]['offer_check'] = intval($tmp_allow[1]);
						$tmp_allow = sprintf("%02b", $val['allow_market']);
						$data[$val['id']]['market_allow'] = intval($tmp_allow[0]);
						$data[$val['id']]['market_check'] = intval($tmp_allow[1]);
						$tmp_allow = sprintf("%02b", $val['allow_company']);
						$data[$val['id']]['company_allow'] = intval($tmp_allow[0]);
						$data[$val['id']]['company_check'] = intval($tmp_allow[1]);
						$tmp_allow = sprintf("%02b", $val['allow_product']);
						$data[$val['id']]['product_allow'] = intval($tmp_allow[0]);
						$data[$val['id']]['product_check'] = intval($tmp_allow[1]);
						$tmp_allow = sprintf("%02b", $val['allow_job']);
						$data[$val['id']]['job_allow'] = intval($tmp_allow[0]);
						$data[$val['id']]['job_check'] = intval($tmp_allow[1]);
						$tmp_allow = sprintf("%02b", $val['allow_companynews']);
						$data[$val['id']]['companynews_allow'] = intval($tmp_allow[0]);
						$data[$val['id']]['companynews_check'] = intval($tmp_allow[1]);
						$tmp_allow = sprintf("%02b", $val['allow_album']);
						$data[$val['id']]['album_allow'] = intval($tmp_allow[0]);
						$data[$val['id']]['album_check'] = intval($tmp_allow[1]);
						$data[$val['id']]['auth_level'] = intval($val['exempt']);
					}
				}
				$curdata = "\$_PB_CACHE['$cachename'] = ".$this->evalArray($data).";\n\n";
				break;
			case "javascript":
				$this->view->clearConfig();
				$this->view->configLoad("default.conf", "javascript");
				$js_vars = $this->view->getConfigVars();
				//arrTemplate is the language pack
				$op = "<!--// Created ".date("M j, Y, G:i")." -->\n";
				$op .= "var pb_lang = { \n";
				if(!empty($js_vars)){
					foreach ($js_vars as $key=>$val) {
						$val = str_replace("\"", "", $val);
						$op .= "\t".strtoupper(trim($key, '_')).' : "'.$val.'",';
						$op .="\n";
					}
				}
				$op .="\tEND : ''\n};";
				$fp = file_put_contents($this->cache_path. "locale.js", $op);
				unset($op);
				break;
			default:
				break;
		}
		return $curdata;
	}
	
	function convert2utf8($str, $force = false)
	{
		global $charset;
		if ($charset!="utf-8") {
			if(is_array($str)){
				return array_map(array('Caches','convert2utf8'), $str);
			}else{
				return iconv($charset, "utf-8", $str);
			}
		}else{
			return $str;
		}
	}
	
	function cacheAll()
	{
		if (!empty($this->lang_dirname)) {
			$this->cache_path = PHPB2B_ROOT."data".DS."cache".DS.$this->lang_dirname.DS;
		}else{
			$this->cache_path = CACHE_PATH;
		}
		$this->writeCache("setting", "setting");
//		$this->updateLanguages();
		$this->writeCache("javascript", "javascript");
		$this->updateIndexCache();
		$this->updateTypes();
		$this->writeCache("area", "area");
		$this->updateTypevars();
		$this->writeCache("industry", "industry");
		$this->writeCache("nav", "nav");
		$this->writeCache("userpage", "userpage");
		$this->writeCache("trusttype", "trusttype");
		$this->writeCache("membergroup", "membergroup");
		$this->writeCache("form", "form");
		$this->writeCache("country", "country");
		return true;
	}
	
	/**
	 * version 4.3
	 * javascript language
	 * @description 
	 */
	function updateLanguages()
	{
		return;
	}
	
	function lang_load($params, &$smarty)
	{
		return;
	}
}
?>