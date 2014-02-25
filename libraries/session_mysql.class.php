<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
class PbSessions {
	var $save_handler = "database";//memcache,mysql,apc
	var $security = "high";//high,medium,low
	var $lifetime = 1440;
	var $sess_table;
	var $table_prefix;
	var $db;
	var $id;
	var $time;
	var $sesskey;
	var $expiry;
	var $last_activity;
	var $expireref;
	var $data;

    function __construct() {
    	session_set_save_handler(array(&$this,'open'), array(&$this,'close'), array(&$this,'read'), array(&$this,'write'), array(&$this,'destroy'), array(&$this,'gc'));
		session_cache_limiter('private, must-revalidate');
		session_start();
    }
    
    function __construct()
    {
    	$this->PbSessions();
    }

    function open($save_path, $session_name, $persist = null) {
		global $pdb, $tb_prefix, $time_stamp;
	    $this->time = $time_stamp;
		$this->table_prefix = $tb_prefix;
		$this->sess_table = $tb_prefix.'sessions';
		$this->db = &$pdb;
		return true;
    }

    function close() {
		$this->gc();
        return true;
    } 

    function read($sid) {
		$result = $this->db->GetRow("SELECT data FROM {$this->sess_table} WHERE sesskey='$sid'");
		return $result ? $result['data'] : null;
    } 

    function write($sid, $sess_data) {
		$sess_data = pb_addslashes($sess_data);
		$expiry = $this->time+$this->lifetime;
		$sql = "SELECT * FROM {$this->sess_table} WHERE sesskey='{$sid}'";
		$result = $this->db->GetRow($sql);
		if(!empty($result)){
			$sql = "UPDATE {$this->sess_table} SET data='$sess_data',expiry='$expiry',modified='$this->time' WHERE sesskey='{$sid}'";
			$this->db->Execute($sql);
		}else{
			$this->db->Execute("INSERT INTO {$this->sess_table} (sesskey,data,expiry,expireref,created,modified) VALUES('$sid', '$sess_data', '$expiry', '".pb_getenv('PHP_SELF')."', '$this->time', '$this->time')");
		}
		return true;
    } 

    function destroy($sid) { 
		$this->db->query("DELETE FROM {$this->sess_table} WHERE sesskey='$sid'");
		return true;
    } 

	function gc() {
		$expiretime = $this->time-$this->lifetime;
		$this->db->Execute("DELETE FROM {$this->sess_table} WHERE expiry<{$expiretime}");
		return true;
    }
    
    function unserializes($data) {
    	$vars = preg_split(
    	'/([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\|/',
    	$data, -1, PREG_SPLIT_NO_EMPTY |
    	PREG_SPLIT_DELIM_CAPTURE
    	);
    	for ($i = 0; isset($vars[$i]); $i++) {
    		$result[$vars[$i++]] = unserialize($vars[$i]);
    	}
    	return $result;
    }
}
?>