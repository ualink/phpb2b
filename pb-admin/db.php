<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2197 $
 */
if( !ini_get('safe_mode') ){
	set_time_limit(3600);
}
require("../libraries/common.inc.php");
require("session_cp.inc.php");
require(LIB_PATH. "db_mysql.inc.php");
uses("log");
$log = new Logs();
$db = new DB_Sql();
$conn = $db->connect($dbname,$dbhost,$dbuser,$dbpasswd);
$tpl_file = "db";
$sizelimit = 204800;//default 2MB
if(!$backupdir = $pdb->GetOne("SELECT valued FROM {$tb_prefix}settings WHERE variable='backup_dir'")) {
	$backupdir = pb_radom(6);
	$db->query("REPLACE INTO {$tb_prefix}settings (variable, valued) values ('backup_dir', '$backupdir')");
}
require(LIB_PATH. "func.db.php");
require(LIB_PATH. "func.sql.php");
if (!is_dir(DATA_PATH. "backup_".$backupdir)) {
	pb_create_folder(DATA_PATH. "backup_".$backupdir);
}
if (isset($_POST['do'])) {
	$do = trim($_POST['do']);
	if ($do == "query" && !empty($_POST['sql_content'])) {
		if ($admin_runquery) {
			$result = sql_run($_POST['sql_content']);
		if($result){
			flash("success");
		}else{
			flash();
		}
	 }else{
			flash("admin_runquery_forbidden");
		}
	}
}
if (isset($_GET['do'])) {
	$do = trim($_GET['do']);
	if ($do=="backup") {
		if (!empty($_GET['filename'])) {
			$filename = $_GET['filename'];
		}else{
			$filename = date('ymd').'_'.pb_radom(6);
		}
		$db->query('SET SQL_QUOTE_SHOW_CREATE=0', 'SILENT');
		$time = gmdate("M d Y H:i:s",mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")));
		$tables = arraykeys2(fetchtablelist($tb_prefix), 'Name');
		if(mysql_get_server_info() > '4.1'){
			$db->query("set names '".$dbcharset."'");
		}
		$backupfilename = DATA_PATH. "backup_".$backupdir.DS.str_replace(array('/', '\\', '.'), '', $filename);
		$volume = intval($_GET['volume']) + 1;
		if($_GET['method'] == 'multivol') {
			$sqldump = '';
			$tableid = intval($_GET['tableid']);
			$startfrom = intval($_GET['startfrom']);
			$complete = TRUE;
			for(; $complete && $tableid < count($tables) && strlen($sqldump) + 500 < $sizelimit * 1000; $tableid++) {
				$sqldump .= sqldumptable($tables[$tableid], $startfrom, strlen($sqldump));
				$startfrom = 0;
			}
			$dumpfile = $backupfilename."-%s".'.sql';
			@unlink($dumpfile);
			!$complete && $tableid--;
			if(trim($sqldump)) {
				$sqldump = "# PHPB2B Data Dump Vol.$volume\n".
					"# Version: PHPB2B ".PHPB2B_VERSION."\n".
					"# Time: ".date("Y-m-d H:i:s")."\n".
					"# IP Address: ".pb_get_client_ip()."\n".
					"# Table Prefix: $tb_prefix\n".
					"#\n".
					"# --------------------------------------------------------\n\n\n".
					$sqldump;
				$dumpfilename = sprintf($dumpfile, $volume);
				$fp = file_put_contents($dumpfilename, $sqldump);
				unset($sqldump);
				$result = $pdb->Execute("UPDATE {$tb_prefix}settings SET valued=".$time_stamp." WHERE variable='last_backup'");
				flash("backup_and_wait", 'db.php?do=backup&filename='.rawurlencode($filename)."&method=multivol&"."&volume=".rawurlencode($volume)."&tableid=".rawurlencode($tableid)."&startfrom=".rawurlencode($startrow)."&extendins=".rawurlencode($extendins), 2, $volume);
			}else{
				$volume--;
				flash("success", "db.php?do=restore");
			}
		}else{
			$sqldump = '';
			$tableid = 0;
			$startfrom = 0;
			$complete = TRUE;
			for(; $tableid < count($tables) ; $tableid++) {
				$sqldump .= sqldumptable($tables[$tableid], $startfrom, strlen($sqldump));
				$startfrom = 0;
			}
			$dumpfile = $backupfilename.'.sql';
			@unlink($dumpfile);
			!$complete && $tableid--;
			if(trim($sqldump)) {
				$fp = file_put_contents($dumpfile, $sqldump);
				unset($sqldump);
				$result = $pdb->Execute("UPDATE {$tb_prefix}settings SET valued=".$time_stamp." WHERE variable='last_backup'");
				$data['handle_type'] = 'info';
				$data['source_module'] = 'backup';
				$data['description'] = $_POST['message'];
				$result = $log->Add($data);
				if($result){
					flash("success", 'db.php?do=restore');
				}else{
					flash("failed", "db.php", 0);
				}
			}else{
				flash();
			}
		}
	}
	if ($do == "refresh" && !empty($_GET['id'])) {
		$datafile = DATA_PATH."backup_".$backupdir.DS.$_GET['id'];
		if(!file_exists($datafile)) {
			flash("file_not_exists");
		}else{
			if(@$fp = fopen($datafile, 'rb')) {
				$sqldump = fread($fp, filesize($datafile));
				fclose($fp);
				$sqlquery = splitsql($sqldump);
				unset($sqldump);
				foreach($sqlquery as $sql) {
					$sql = syntablestruct(trim($sql), $db->version() > '4.1', $dbcharset);
					if($sql != '') {
						$db->query($sql);
						if(($sqlerror = $db->error()) && $db->errno() != 1062) {
							$db->halt('MySQL Query Error', $sql);
						}
					}
				}
				flash("db_restored", "db.php?do=restore");
			}else{
				flash();
			}
		}
	}
	if($do =="del" &&!empty($_GET['id'])){
		$datafile = DATA_PATH."backup_".$backupdir.DS.$_GET['id'];
		if(!file_exists($datafile)) {
			flash("file_not_exists");
		}else{
			@unlink($datafile);
		}
		flash("success", "db.php?do=restore");
	}
	switch ($do) {
		case "query":
			$tpl_file = "db.query";
			break;
		case "restore":
			$smarty->register_modifier('get_custom_size', 'size_info');
			$narray = array();
			$dir = DATA_PATH. "backup_".$backupdir.DS;
			if (is_dir($dir)) {
				$backed_dir = dir($dir);
				$i = -1;
				while($entry = $backed_dir->read()) {
					if(!in_array($entry, array('.', '..','.svn'))) {
						$narray[] = array(
						'name' => $entry,
						'directory' => DATA_PATH. "backup_".$backupdir.DS.$entry,
						'filemtime' => date("Y-m-d H:i:s", @filemtime($dir.DS.$entry)),
						'filesize' => @filesize($dir.DS.$entry),
						);
					}
				}				
			}

			uasort($narray, 'filemtimesort');
			if (!empty($narray)) {
				setvar("Items", $narray);
			}
			$tpl_file = "db.restore";
			break;
		default:
			break;
	}
}
setvar("backup_dir", $backupdir);
$lastbackup_time = $pdb->GetOne("SELECT valued FROM {$tb_prefix}settings WHERE variable='last_backup'");
if ($lastbackup_time) {
	setvar("LastbackupTime", date("Y-m-d H:i", $lastbackup_time));
}
template($tpl_file);
?>