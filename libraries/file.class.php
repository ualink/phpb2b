<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
define('GLOB_NODIR',256);
define('GLOB_PATH',512);
define('GLOB_NODOTS',1024);
define('GLOB_RECURSE',2048);
if (!function_exists('fnmatch')) {
    function fnmatch($pattern, $string) {
        return @preg_match('/^' . strtr(addcslashes($pattern, '\\.+^$(){}=!<>|'), array('*' => '.*', '?' => '.?')) . '$/i', $string);
    }
}
class Files extends PbObject
{
	var $mFolders = array();
	var $mFiles = array();
	var $mDateTime = "Y-m-d H-i-s";
	var $mTimeOffset = 8;
	var $exclude = array();
	var $mode = 0777;
	var $handle = null;
	var $self = array();

	function __construct(){
		parent::__construct();
		$args = func_get_args();
        for( $i=0, $n=count($args); $i<$n; $i++ )
            $this->add($args[$i]);
	}
	
	function add($name = null, $enum = null ) {
		if( isset($enum) )
		$this->self[$name] = $enum;
		else
		$this->self[$name] = end($this->self) + 1;
	}

	function filename_safe($name) {
		$except = array('\\', '/', ':', '*', '?', '"', '<', '>', '|');
		return str_replace($except, '', $name);
	}
	
	function getModes($mod) {
		$modVal = array(
		"0444" => 292,
		"0445" => 293,
		"0446" => 294,
		"0447" => 295,
		"0454" => 300,
		"0455" => 301,
		"0456" => 302,
		"0457" => 303,
		"0464" => 308,
		"0465" => 309,
		"0466" => 310,
		"0467" => 311,
		"0474" => 316,
		"0475" => 317,
		"0476" => 318,
		"0477" => 319,
		"0544" => 356,
		"0545" => 357,
		"0546" => 358,
		"0547" => 359,
		"0554" => 364,
		"0555" => 365,
		"0556" => 366,
		"0557" => 367,
		"0564" => 372,
		"0565" => 373,
		"0566" => 374,
		"0567" => 375,
		"0574" => 380,
		"0575" => 381,
		"0576" => 382,
		"0577" => 383,
		"0644" => 420,
		"0645" => 421,
		"0646" => 422,
		"0647" => 423,
		"0654" => 428,
		"0655" => 429,
		"0656" => 430,
		"0657" => 431,
		"0664" => 436,
		"0665" => 437,
		"0666" => 438,
		"0667" => 439,
		"0674" => 444,
		"0675" => 445,
		"0676" => 446,
		"0677" => 447,
		"0744" => 484,
		"0745" => 485,
		"0746" => 486,
		"0747" => 487,
		"0754" => 492,
		"0755" => 493,
		"0756" => 494,
		"0757" => 495,
		"0764" => 500,
		"0765" => 501,
		"0766" => 502,
		"0767" => 503,
		"0774" => 508,
		"0775" => 509,
		"0776" => 510,
		"0777" => 511);

		return $modVal[$mod];
	}
	
	function nofate_mkdir($dir){
		$u=umask(0);
		$r=mkdir($dir,$this->mode);
		umask($u);
		return $r;
	}
	
	function moveDir($oldpath, $newpath)
	{
		if (function_exists("rename")) {
			rename($oldpath, $newpath);
		}
	}

	function dir_writeable($dir) {
		if(!is_dir($dir)) {
			@mkdir($dir, $this->mode);
		}
		if(is_dir($dir)) {
			if($fp = @fopen("$dir/pb_sample.txt", 'w')) {
				@fclose($fp);
				@unlink("$dir/pb_sample.txt");
				$writeable = true;
			} else {
				$writeable = false;
			}
		}else{
			return is_writable($dir);
		}
		return $writeable;
	}
	
	function file_writeable($file_name)
	{
		return is_writable($file_name);
	}

	function mkDirs ($dir) {
		$dir = str_replace("\\","/",$dir);
		$dirs = explode('/', $dir);
		$total = count($dirs);
		$temp = '';
		for($i=0; $i<$total; $i++) {
			$temp .= $dirs[$i].'/';
			if (!is_dir($temp)) {
				if (!@mkdir($temp)) return;
				@chmod($temp, 0777);
			}
		}
	}
	
	function rmDirs ($dir, $rmself = false, $rmdir = false) {
		if(substr($dir,-1)=="/"){
			$dir=substr($dir,0,-1);
		}
		if(!file_exists($dir)||!is_dir($dir)){
			return false;
		} elseif(!is_readable($dir)){
			return false;
		} else {
			$dirs= opendir($dir);
			while (false !== ($entry=readdir($dirs))) {
				if ($entry!="." && $entry!=".." && $entry!=".svn" && !in_array($entry, $this->exclude)) {
					$path = $dir."/".$entry;
					if(is_dir($path)){
						if ($rmdir) {
							rmdir($path);
						}else{
							$this->rmDirs($path);
						}
					} else {
						unlink($path);
					}
				}
			}
			closedir($dirs);
			if($rmself){
				if(!rmdir($dir)){
					return false;
				}
				return true;
			}
		}
	}
	
	function delFile ($file) {
		if ( !is_file($file) ) return false;
		@unlink($file);
		return true;
	}
	
	function createFile ($file, $content="", $mode="w") {
		if ( in_array($mode, array("w", "a")) ) $mode = "w";
		if ( !$hd = fopen($file, $mode) ) return false;
		if ( !false === fwrite($hd, $content) ) return false;
		return true;
	}
	
	function getFolders ($dir) {
		$this->mFolders = Array();
		if(substr($dir,-1)=="/"){
			$dir=substr($dir,0,-1);
		}
		if(!file_exists($dir)||!is_dir($dir)){
			return false;
		}
		$dirs= opendir($dir);
		$i = 0;
		while (false!==($entry=readdir($dirs))) {
			if ($entry!="." && $entry!=".." && $entry!=".svn") {
				$path=$dir."/".$entry;
				if(is_dir($path)){
					$filetime = @filemtime($path);
					$filetime = @date($this->mDateTime, $filetime+3600*$this->mTimeOffset);
					$this->mFolders[$i]['name'] = $entry;
					$this->mFolders[$i]['filetime'] = $filetime;
					$this->mFolders[$i]['filesize'] = 0;
					$i++;
				}
			}
		}
		return $this->mFolders;
	}
	
	function getFiles ($dir) {
		$this->mFiles = Array();
		if(substr($dir,-1)=="/"){
			$dir=substr($dir,0,-1);
		}
		if(!file_exists($dir)||!is_dir($dir)){
			return false;
		}
		$dirs= opendir($dir);
		$i = 0;
		while (false!==($entry=readdir($dirs))) {
			if ($entry!="."&&$entry!="..") {
				$path=$dir."/".$entry;
				if(is_file($path)){
					$filetime = @filemtime($path);
					$filetime = @date($this->mDateTime, $filetime+3600*$this->mTimeOffset);
					$filesize = $this->getFileSize($path);
					$this->mFiles[$i]['name'] = $entry;
					$this->mFiles[$i]['filetime'] = $filetime;
					$this->mFiles[$i]['filesize'] = $filesize;
					$i++;
				}
			}
		}
		return $this->mFiles;
	}
	
	function getFileSize ($file) {
		if ( !is_file($file) ) return 0;
		$f1 = $f2 = "";
		$filesize = @filesize("$file");
		if ( $filesize > 1073741824 ) {
		} elseif ( $filesize > 1048576 ) {
			$filesize = $filesize / 1048576;
			list($f1, $f2) = explode(".",$filesize);
			$filesize = $f1.".".substr($f2, 0, 2)."MB";
		} elseif ( $filesize > 1024 ) {
			$filesize = $filesize / 1024;
			list($f1, $f2) = explode(".",$filesize);
			$filesize = $f1.".".substr($f2, 0, 2)."KB";
		} else {
			$filesize = $filesize."Bytes";
		}
		return $filesize;
	}
	
	/**
	 * Remove the directory and its content (all files and subdirectories).
	 * @param string $dir the directory name
	 */
	function rmrf($dir) {
		foreach (glob($dir) as $file) {
			if (is_dir($file)) {
				$this->rmrf("$file/*");
				rmdir($file);
			} else {
				unlink($file);
			}
		}
	}
	
	/**
	 * $str = "foobar and blob\netc.";
	 * match_wildcard('foo*', $str),      // TRUE
	 */
	function match_wildcard( $wildcard_pattern, $haystack ) {
	   $regex = str_replace(
	     array("\*", "\?"), // wildcard chars
	     array('.*','.'),   // regexp chars
	     preg_quote($wildcard_pattern)
	   );
	
	   return preg_match('/^'.$regex.'$/is', $haystack);
	}
	
	function rfr($path,$match){
		static $deld = 0, $dsize = 0;
		$dirs = glob($path."*");
		$files = glob($path.$match);
		foreach($files as $file){
			if(is_file($file)){
				$dsize += filesize($file);
				unlink($file);
				$deld++;
			}
		}
		foreach($dirs as $dir){
			if(is_dir($dir)){
				$dir = basename($dir) . "/";
				$tihs->rfr($path.$dir,$match);
			}
		}
		return true;
	}
	
	function safe_glob($pattern, $flags=0) {
		$split=explode('/',str_replace('\\','/',$pattern));
		$mask=array_pop($split);
		$path=implode('/',$split);
		if (($dir=opendir($path))!==false) {
			$glob=array();
			while(($file=readdir($dir))!==false) {
				// Recurse subdirectories (GLOB_RECURSE)
				if( ($flags&GLOB_RECURSE) && is_dir($file) && (!in_array($file,array('.','..'))) )
				$glob = array_merge($glob, array_prepend($this->safe_glob($path.'/'.$file.'/'.$mask, $flags),
				($flags&GLOB_PATH?'':$file.'/')));
				// Match file mask
				if (fnmatch($mask,$file)) {
					if ( ( (!($flags&GLOB_ONLYDIR)) || is_dir("$path/$file") )
					&& ( (!($flags&GLOB_NODIR)) || (!is_dir($path.'/'.$file)) )
					&& ( (!($flags&GLOB_NODOTS)) || (!in_array($file,array('.','..'))) ) )
					$glob[] = ($flags&GLOB_PATH?$path.'/':'') . $file . ($flags&GLOB_MARK?'/':'');
				}
			}
			closedir($dir);
			if (!($flags&GLOB_NOSORT)) sort($glob);
			return $glob;
		} else {
			return false;
		}
	}
}
?>