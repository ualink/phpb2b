<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
require("session_cp.inc.php");
$tpl_file = "checkfile";
$md5datanew = $md5data = $data = $dirlist = array();
$flag  = 0;
if(isset($_POST['check'])){
	$data = @file('pbfiles.md5');
	checkfiles('../','\.php');
	foreach($data as $line) {
		$file = trim(substr($line, 34));
		$md5datanew[$file] = substr($line, 0, 32);
		if($md5datanew[$file] != $md5data[$file]) {
			$modifylist[$file] = $md5data[$file];
		}
		$md5datanew[$file] = $md5data[$file];
}
$addlist = @array_diff_assoc($md5data,$md5datanew);
$dellist = @array_diff_assoc($md5datanew,$md5data);
$modifylist =@array_diff_assoc($modifylist, $dellist);
$showlist = @array_merge($md5data,$md5datanew);
foreach($showlist as $file => $md5) {
	$dir = dirname($file);
	if(@array_key_exists($file, $modifylist)) {
		$fileststus = 'Modify';
	} elseif(@array_key_exists($file, $dellist)) {
		$fileststus = 'Delete';
	} elseif(@array_key_exists($file, $addlist)) {
		$fileststus = 'Add';
	} else {
		$fileststus = '';
	}
	if(file_exists($file)) {
		$filemtime = @filemtime($file);
		$fileststus && $dirlist[] = array('name'=>$dir."/".basename($file), 'size'=>number_format(filesize($file)).' Bytes', 'time'=>date('Y-m-d H:m:s', $filemtime),'status'=>$fileststus);
	} else {
		$fileststus && $dirlist[] = array('name'=>$dir."/".basename($file),'size'=>'', 'time'=>'','status'=>$fileststus);
	}
}

$flag = 1;
setvar("Items",$dirlist);
}
setvar("flag",$flag);
template($tpl_file);
function checkfiles($currentdir, $ext = '', $sub = 1, $skip = '') {
	global $md5data;
	$dir = @opendir($currentdir);
	$exts = '/('.$ext.')$/i';
	$skips = explode(',', $skip);
	while($entry = @readdir($dir)) {
		$file = $currentdir.$entry;
		if($entry != '.' && $entry != '..'&& $entry != '.svn' && (preg_match($exts, $entry) || $sub && is_dir($file)) && !in_array($entry, $skips) && $entry != 'data') {
			if($sub && is_dir($file)) {
				checkfiles($file.'/', $ext, $sub, $skip);
			} else {
				$md5data[$file] = md5_file($file);
			}
		}
	}
}
?>