<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2248 $
 */
class PbModel
{
	public static $instance;
	public $param;
	public $displaypg = 9;
	var $primaryKey = "id";
	var $id = null;
	var $catchIds = null;
	var $table;
	var $limit = 1;
	var $offset = 0;
	var $limit_offset = 1;
	var $dbstuff;
	var $table_prefix;
	var $condition;
	var $params;
	var $timestamp;
	var $dateline;
	var $table_name;
	var $orderby;
	var $validate = array();
	var $validationErrors = array();
	var $page_secure = false;
	var $cache_sql = 0;//180 seconds, if no, set 0.
	
	function __construct()
	{
		global $pdb, $tb_prefix, $time_stamp;
		$this->dbstuff = $pdb;
		$this->timestamp = $time_stamp;
		$this->table_prefix = $tb_prefix;
		$this->param = $GLOBALS['G'];
	}
	
	public function getInstance()
	{
		if (self::$instance == NULL) { // If instance is not created yet, will create it.
			self::$instance = new PbModel();
		}
		return self::$instance;
	}
	
	function __call($method, $params) {
		$return = $this->query($method, $params, $this);
		return $return;
	}
	
	//echo 'Your val is $name,and is not exsit in this class!';
	public function __get($property) {
		return $this->$property;
	}
	
	//echo 'Your val is '.$name.'=>'.$value;
	public function __set($property, $value) {
		$this->$property = $value;
	}	
	
	function query() {
		$args	  = func_get_args();
		$fields	  = null;
		$order	  = null;
		$limit	  = null;
		$page	  = null;
		$recursive = null;
		if (count($args) == 1) {
			return $this->Execute($args[0]);
		}elseif (count($args) > 1 && (strpos(strtolower($args[0]), 'findby') === 0 || strpos(strtolower($args[0]), 'findallby') === 0)) {
			$params = $args[1];
			if (strpos(strtolower($args[0]), 'findby') === 0) {
				$all  = false;
				$field = preg_replace('/^findBy/i', '', $args[0]);
			} else {
				$all  = true;
				$field = preg_replace('/^findAllBy/i', '', $args[0]);
			}
			
			$field = strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $field));
			
			$or = (strpos($field, '_or_') !== false);
			if ($or) {
				$field = explode('_or_', $field);
			} else {
				$field = explode('_and_', $field);
			}
			
			$off = count($field) - 1;

			if (isset($params[1 + $off])) {
				$fields = $params[1 + $off];
			}

			if (isset($params[2 + $off])) {
				$order = $params[2 + $off];
			}

			if (!array_key_exists(0, $params)) {
				return false;
			}
			$c = 0;
			$conditions = array();

			foreach ($field as $f) {
				$conditions[$args[2]->name . '.' . $f] = $params[$c];
				$c++;
			}

			if ($or) {
				$conditions = array('OR' => $conditions);
			}
			
			foreach ($conditions as $key=>$val) {
				$condition = $key."='".$val."'";
			}
			$fields = empty($fields)?"*":$fields;
			$this->setCondition($condition);
			$sql = "SELECT {$fields} FROM ".$this->getTable(true).$this->getCondition();
			unset($this->condition);
			return $this->dbstuff->GetRow($sql);
		}
	}
	
	function setParams($extra = array()) {
		$params = array();
		if (isset($_POST)) {
			$params['form'] = $_POST;
			if (ini_get('magic_quotes_gpc') === '1') {
				$params['form'] = pb_addslashes($params['form']);
			}
			if (pb_getenv('HTTP_X_HTTP_METHOD_OVERRIDE')) {
				$params['form']['_method'] = pb_getenv('HTTP_X_HTTP_METHOD_OVERRIDE');
			}
			if (isset($params['form']['_method'])) {
				if (isset($_SERVER) && !empty($_SERVER)) {
					$_SERVER['REQUEST_METHOD'] = $params['form']['_method'];
				} else {
					$_ENV['REQUEST_METHOD'] = $params['form']['_method'];
				}
				unset($params['form']['_method']);
			}
		}
		$params = array_merge($extra, $params);
		if (isset($_GET)) {
			if (ini_get('magic_quotes_gpc') === '1') {
				$url = stripslashes_deep($_GET);
			} else {
				$url = $_GET;
			}
			array_unique($url);
			if (isset($params['url'])) {
				$params['url'] = array_merge($params['url'], $url);
			} else {
				$params['url'] = $url;
			}
		}		
		if (isset($params['action']) && strlen($params['action']) === 0) {
			$params['action'] = 'list';
		}
		if (isset($params['form']['data'])) {
			$params['data'] = $params['form']['data'];
			unset($params['form']['data']);
		}
		$this->params = $params;
	}

	function getParams()
	{
		return $this->params;
	}
	
	function setLimitOffset($offset, $limit)
	{
		$offset = empty($offset)?'':$offset.",";
		$limit = empty($limit)?1:$limit;
//		$this->param['start'] = $offset;
//		$this->param['limit'] = $limit;
		$this->limit_offset = $offset.$limit;
	}
	
	function getLimitOffset()
	{
		if($this->limit_offset===0) {
			return;
		}elseif (!empty($this->limit_offset)) {
			return " LIMIT ".$this->limit_offset;
		}else{
			return;
		}
	}
	
	function setOrderby($orderby)
	{
		if (!$orderby || empty($orderby)) {
			return;
		}else{
			if (is_array($orderby)) {
				$tmp_str = implode(",", $orderby);
			}else{
				$tmp_str = $orderby;
			}
			$this->orderby = " ORDER BY ".$tmp_str." ";
		}
	}
	
	function getOrderby()
	{
		return $this->orderby;
	}

	function setPrimaryKey($p = null)
	{
		if (is_null($p)) {
			$p = "id";
		}
		$this->primaryKey = $p;
	}

	function getPrimaryKey()
	{
		return $this->primaryKey;
	}

	function del($ids, $conditions = null, $table = null)
	{
		$del_id = $this->primaryKey;
		$tmp_ids = $condition = null;
		if (is_array($ids))
		{
			$tmp_ids = implode(",",$ids);
			$cond[] = "{$del_id} IN ({$tmp_ids})";
			$this->catchIds = serialize($ids);
		}
		else
		{
			$cond[] = "{$del_id}=".intval($ids);
			$this->catchIds = $ids;
		}
		if (!empty($table)) {
			$table_name = $this->table_prefix.$table;
		}else{
			$table_name = $this->getTable();
		}
		if(!empty($conditions)) {
			if(is_array($conditions)) {
				$tmp_where_cond = implode(" AND ", $conditions);
				$cond[] = $tmp_where_cond;
			}
			else {
				$cond[] = $conditions;
			}
		}
		$this->setCondition($cond);
		$sql = "DELETE FROM ".$table_name.$this->getCondition();
		$deleted = $this->dbstuff->Execute($sql);
		unset($this->condition);
		return $deleted;
	}

	function save($posts, $action=null, $id=null, $tbname = null, $conditions = null, $if_check_word_ban = false)
	{
		$new_id = $result = false;
		$keys = array_keys($posts);
		$cols = implode($keys,",");
		$tbname = (is_null($tbname))? $this->getTable():trim($tbname);
		$this->table_name = $tbname;
		//Todo:2010.04.14, by john
		if(!empty($id)){
			$sql = "SELECT $cols FROM ".$tbname." WHERE ".$this->primaryKey."='".$id."'";
		}elseif(!empty($posts[$this->primaryKey])){
			$sql = "SELECT $cols FROM ".$tbname." WHERE ".$this->primaryKey."='".$posts[$this->primaryKey]."'";
		}else{
			$sql = "SELECT $cols FROM ".$tbname." WHERE ".$this->primaryKey."='-1'";
		}
		if (!is_null($conditions)) {
			if (!empty($conditions)) {
				if (is_array($conditions)) {
					$condition = implode(" AND ", $conditions);
				}else{
					$condition = $conditions;
				}
			}
			$sql.= " AND ".$condition;
		}
		$rs = $this->dbstuff->Execute($sql);
		$record = array();
		foreach ($keys as $colname) {
			$sp_search = array('\\\"', "\\\'", "'","&nbsp;", '\n','\\\&quot;');
			$sp_replace = array('&quot;', '&#39;', '&#39;',' ', '<br />','');
			$slash_col = str_replace($sp_search, $sp_replace, $posts[$colname]);
			if (!defined("IN_PBADMIN")) {
				$slash_col = sens_str($slash_col);
			}
			$record[$colname] = stripslashes($slash_col);	
		}
		if (!defined("IN_PBADMIN") && isset($record['id'])) {
			unset($record['id']);
		}
		if (strtolower($action) == "update") {
			$insertsql = $this->dbstuff->GetUpdateSQL($rs,$record);
		    $new_id = false;
		}else {
			$insertsql = $this->dbstuff->GetInsertSQL($rs,$record);
			$new_id = true;
		}
		if($insertsql) $result = $this->dbstuff->Execute($insertsql);
		if (!$result || empty($result)) {
			return false;
		}else {
		    if($new_id){
		        $insert_key = $tbname."_id";
		        $this->$insert_key = $this->dbstuff->Insert_ID();
		    }
			return true;
		}
	}

	function read($fields = null, $id = null, $tables = null, $conditions = null)
	{
		$tmp_tablename = null;
		if ($id!==null) {
			$this->id = $id;
		}
		$id = $this->id;
		if (is_array($this->id)) {
			$id = $this->id[0];
		}
		if($tables == null){
			$tmp_tablename = $this->getTable(true);
		}
		if (is_null($fields)) {
			$fields = null;
			$columns = $this->dbstuff->MetaColumnNames($this->getTable());
			foreach ($columns as $key=>$val) {
				$fields.=$key." AS ".$this->name.$this->format_column($val).",";
			}
			$fields = substr_replace($fields,'',-1,1);
		}
		$sql = null;

		if ($this->id !== null && $this->id !== false) {
			$field = trim($this->name).".".$this->primaryKey;
		}
		$sql = "SELECT ".$fields." FROM ".$tmp_tablename." WHERE ".$field."='".$id."'";
		if(!empty($conditions)){
			if (is_array($conditions)) {
				$tmp_condition = implode(" AND ", $conditions);
			}else{
				$tmp_condition = $conditions;
			}
			$sql.= " AND ".$tmp_condition;
		}
		$res = $this->dbstuff->GetRow($sql);
		return $res;
	}

	function field($name, $conditions = null, $order = null) {
		if ($conditions === null) {
			$conditions = array($this->name . '.' . $this->primaryKey => $this->id);
		}
		if (is_array($conditions)) {
			$tmp_conditions = implode(" AND ",$conditions);
			$conditions = $tmp_conditions;
		}
		$sql = "select ".trim($name)." from ".$this->getTable(true)." where ".$conditions;
//		if ($this->cache_sql) {
//			$return = $this->dbstuff->CacheGetOne($this->cache_sql, $sql);
//		}else{
			$return = $this->dbstuff->GetOne($sql);
//		}
		return $return;
	}

	function getFieldAliasNames()
	{
		$table_name = $this->getTable();
		$fields = null;
		$columns = $this->dbstuff->MetaColumnNames($table_name);
		foreach ($columns as $key=>$val) {
			$fields.=$this->name.".".$key." as ".$this->name.$this->format_column($val).",";
		}
		$fields = substr_replace($fields,'',-1,1);
		return $fields;
	}
	
	function setCondition($conditions)
	{
		$return  = null;
		if(empty($conditions)) return;
		if (is_array($conditions)) {
			$tmp_condition = implode(" AND ", $conditions);
		}else{
			$tmp_condition = $conditions;
		}
		$return = " WHERE ".$tmp_condition;
		$this->condition = $return;
	}
	
	function getCondition()
	{
		return $this->condition;
	}

	function getTable($alias = false, $join_name = '')
	{
		global $tb_prefix;
		$table = $tb_prefix.strtolower(get_class($this));
		if (!empty($join_name)) {
			$this->name = $join_name;
		}
		if($alias) $table.= " AS ".$this->name;
		return $table;
	}

	function findCount($joins = null, $conditions = null, $countfield = null, $table_alias = null)
	{
		if (!$this->page_secure) {
			if (isset($_GET['total_record'])) {
				return intval($_GET['total_record']);
			}elseif (isset($_GET['total_count'])){
				return intval($_GET['total_count']);
			}
		}
		$sql = $multi_table = null;
		if(empty($countfield)) $countfield = $this->primaryKey;
		if (empty($table_alias)) {
			$tables = $this->getTable(true);
		}else{
			$tables = $this->getTable(true, $table_alias);
		}
		if (!empty($joins)) {
			$multi_table = implode(" ", $joins);
		}
		$sql = "SELECT count(".$countfield.") AS amount FROM ".$tables." ".$multi_table;
		if (!empty($conditions)) {
			if (is_array($conditions) && !empty($conditions)) {
				$tmp_condition = implode(" AND ", $conditions);
			}else{
				$tmp_condition = $conditions;
			}
			$sql.= " WHERE ".$tmp_condition." ";
		}elseif (!empty($this->condition)){
			if (is_array($this->condition)) {
				$tmp_condition = implode(" AND ", $this->condition);
			}else{
				$tmp_condition = $this->condition;
			}
			$tmp_condition = str_replace("WHERE", "", $tmp_condition);
			$sql.= " WHERE ".$tmp_condition." ";			
		}
		if ($this->cache_sql) {
			$return = $this->dbstuff->CacheGetOne($this->cache_sql, $sql);
		}else{
			$return = $this->dbstuff->GetOne($sql);
		}
		return $return;
	}

	function findAll($fields = null, $joins = null, $conditions = null, $order = null, $limit = null, $offset = null, $recursive = null)
	{
		global $ADODB_CACHE_DIR, $db_cache_support;
		$orders			= '';
		$records		= '';
//		$condition	 	= null;
		$multi_table	= '';
		if (is_null($fields)) {
			$find_fields[] = $this->name.".*";
		}else{
			$find_fields[] = $fields;
		}
		$fields = implode(",",$find_fields);
		if (!empty($joins)) {
			$multi_table = implode(" ", $joins);
		}
		$sql = "SELECT ".$fields." FROM ".$this->getTable(true)." ".$multi_table;
		if (!empty($conditions)) {
			if (is_array($conditions)) {
				$tmp_condition = implode(" AND ", $conditions);
			}else{
				$tmp_condition = $conditions;
			}
			$sql.= " WHERE ".$tmp_condition;
		}elseif (!empty($this->condition)){
			if (is_array($this->condition)) {
				$tmp_condition = implode(" AND ", $this->condition);
			}else{
				$tmp_condition = $this->condition;
			}
			$tmp_condition = str_replace("WHERE", "", $tmp_condition);
			$sql.= " WHERE ".$tmp_condition;	
		}
		if (!empty($order)) {
			$orders.= " ORDER BY ".$order;
			$sql.= $orders;
		}
		if (isset($this->param['start']) && isset($this->param['limit'])) {
			$limit = intval($this->param['start']);
			$offset = intval($this->param['limit']);
		}
		if (!is_null($limit) && !is_null($offset)) {
			$records = " LIMIT $limit,$offset";
			$sql.=$records;
		}
		if (!empty($ADODB_CACHE_DIR) && $this->cache_sql && !defined("IN_PBADMIN")) {
			$return = $this->dbstuff->CacheGetArray($sql);
		}else{
			$return = $this->dbstuff->GetArray($sql);
		}
		return $return;
	}

	function clicked($id, $additionalParams = true)
	{
		if($additionalParams){
			$sql = "update ".$this->getTable()." set clicked=clicked+1 where id=".$id;
		}
		return $this->dbstuff->Execute($sql);
	}

	 function check($id = null, $status = 0)
	{
		if(is_array($id)){
			$checkId = "id in (".implode(",",$id).")";
		}elseif(intval($id)) {
			$checkId = "id=".$id;
		}else{
			return false;
		}
		$sql = "update ".$this->getTable()." set status='".$status."' where ".$checkId;
		$return = $this->dbstuff->Execute($sql);
		if($return){
			return true;
		}else {
			return false;
		}
	}

	function saveField($name, $value, $id = null, $conditions = null)
	{
		if(is_array($id)){
			$checkId = "id in (".implode(",",$id).")";
		}elseif(is_int($id)) {
			$checkId = "id=".$id;
		}else{
			$checkId = 1;
		}
		if(empty($conditions)) $conditions = 1;
		if($checkId){
			$sql = "update ".$this->getTable()." set $name='".$value."' where ".$checkId." and ".$conditions;
			$return = $this->dbstuff->Execute($sql);
		}
		return $return;
	}

	function getMaxId()
	{
		$sql = "SELECT MAX(id) FROM ".$this->getTable();
		$max_id = $this->dbstuff->GetOne($sql);
		return $max_id;
	}

	function format_column($colname)
	{
	    $new_column_name = null;
	    if (strstr($colname, "_")) {
	        $tmp_col = explode("_", $colname);
	        foreach ($tmp_col as $val) $new_column_name.=ucfirst(strtolower($val));
	    }else {
	        $new_column_name = ucfirst(strtolower($colname));
	    }
	    return $new_column_name;
	}
	
	function doValidation($arr)
	{
		if (empty($this->validate)) {
			return false;
		}
		$validate = array();
		$this->initValidations();
		foreach ($this->validate as $fieldName=>$val) {
			if ($val['required']) {
				if (isset($arr[$fieldName]) && empty($arr[$fieldName])) {
					$this->validationErrors[] = $val['message'];
				}
			}
		}
	}
	
	function initValidations()
	{
		return true;
	}
	
	function GetArray($sql)
	{
		global $ADODB_CACHE_DIR;
		//$this->dbstuff->setFetchMode(ADODB_FETCH_ASSOC);
		if ($this->cache_sql && !empty($ADODB_CACHE_DIR) && !defined("IN_PBADMIN")) {
			return $this->dbstuff->CacheGetArray($this->cache_sql, $sql);	
		}else{
			return $this->dbstuff->GetArray($sql);	
		}
	}

	function GetRow($sql)
	{
		global $ADODB_CACHE_DIR;
		if ($this->cache_sql && !empty($ADODB_CACHE_DIR) && !defined("IN_PBADMIN")) {
			return $this->dbstuff->CacheGetRow($this->cache_sql, $sql);	
		}else{
			return $this->dbstuff->GetRow($sql);	
		}
	}
		
	function GetAll($sql)
	{
		global $ADODB_CACHE_DIR;
		if ($this->cache_sql && !empty($ADODB_CACHE_DIR) && !defined("IN_PBADMIN")) {
			return $this->dbstuff->CacheGetAll($this->cache_sql, $sql);	
		}else{
			return $this->dbstuff->GetAll($sql);	
		}
		
	}
	
	function findIt($table_name, $parent_id = 0)
	{
		$this->dbstuff->setFetchMode(ADODB_FETCH_ASSOC);
		$this->params['result'] = $this->dbstuff->GetArray("SELECT * FROM ".$this->table_prefix."{$table_name} ORDER BY level ASC,id ASC");
		foreach ($this->params['result'] as $key=>$val) {
			$row = $this->getChild($val['id']);
			$_name = pb_lang_split($val['name']);
			$this->params['data'][$val['level']][$val['id']]['id'] = $val['id'];
			$this->params['data'][$val['level']][$val['id']]['title'] = $_name;
			$this->params['data'][$val['level']][$val['id']]['name'] = $_name;
			$this->params['data'][$val['level']][$val['id']]['child'] = $row;
			$this->params['data'][$val['level']][$val['id']]['sub'] = $row;
			if (!empty($row)) {
				$this->params['data'][$val['level']][$val['id']]['child'] = $row;
				$this->params['data'][$val['level']][$val['id']]['sub'] = $row;
			}
		}
	}
	
	function getChild($id)
	{
		$return = array();
		$i = 0;
		foreach ($this->params['result'] as $key=>$val) {
			if ($val['parent_id'] == $id) {
				$this->params['result'][$i]['title'] = $this->params['result'][$i]['name'];
				$this->params['result'][$i]['name'] = $this->params['result'][$i]['name'];
				$return[$val['id']] = $this->params['result'][$i];
			}
			$i++;
		}
		return $return;
	}
 	
 	/**
 	 * Clean up an array, comma- or space-separated list of IDs
 	 *
 	 * @param mixed $list
 	 * @return unknown
 	 */
 	function parseIdList( $list ) {
 		if ( !is_array($list) )
 		$list = preg_split('/[\s,]+/', $list);
 		return array_unique(array_map('abs', $list));
 	}
 	
 	function getIncludeIds( $list )
 	{
 		$include = $this->parseIdList($list);
 		if ( is_array($include) )
 		$include = implode(',', $include);
 		$include = preg_replace('/[^0-9,]/', '', $include); // (int)
 		if ( $include )
 		return " id IN ($include)";
 	}
 	
 	function getExcludeIds( $list )
 	{
 		$exclude = $this->parseIdList($list);
 		if ( is_array($exclude) )
 		$exclude = implode(',', $exclude);
 		$exclude = preg_replace('/[^0-9,]/', '', $exclude); // (int)
 		if ( $exclude )
 		return " id NOT IN ($exclude)";
 	}

	
	function url($params)
	{
		if (!function_exists("smarty_function_the_url")) {
			require(SLUGIN_PATH."function.the_url.php");
		}
		return smarty_function_the_url($params);
	}
		
	//For static, from 2011.3.3
	function getPermaLink(
	$id, 
	$model_url = null, 
	$model = null, 
	$type = "detail")
	{
		global $rewrite_able, $rewrite_compatible;
		$ext = ".html";
		if(empty($model)) {
				$model = strtolower($this->name);
		}
		if (!empty($model_url)) {
			if(strpos($model_url, ".php")===false){
				$model = $model_url;
				unset($model_url);
			}
		}
		if($type == "detail" && !empty($model_url)){
			$lbl = (strpos($model_url, "?")!==false)?"&":"?";
	 		if ($rewrite_able) {
	 			return (!empty($model_url))?$model_url.$lbl."id=".$id:$model. "/detail/".$id.$ext;
	 		}else{
	 			return (!empty($model_url))?$model_url.$lbl."id=".$id:$model."/detail.php".$lbl."id=".$id;
	 		}
		}
	}
	
	function getNeighbour($id, $fields = "*")
	{
		$ret = array();
		$sql_prev = "select {$fields} from ".$this->getTable()." where id<$id order by id desc limit 1";
		$sql_next = "select {$fields} from ".$this->getTable()." where id>$id order by id  limit 1";
		$ret['prev'] = $this->GetRow($sql_prev);
		$ret['next'] = $this->GetRow($sql_next);
		return $ret;
	}
}
?>