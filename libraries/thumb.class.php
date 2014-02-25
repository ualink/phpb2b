<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
class Image {
	var $attachinfo = '';
	var $srcfile = '';
	var $targetfile = '';
	var $imagecreatefromfunc = '';
	var $imagefunc = '';
	var $attach = array();
	var $animatedgif = 0;

	function __construct($srcfile, $targetfile, $attach = array()) {
		$imagelib = 0;
		$this->srcfile = $srcfile;
		$this->targetfile = $targetfile;
		$this->attach = $attach;
		$this->attachinfo = @getimagesize($targetfile);
		if(!$imagelib) {
			switch($this->attachinfo['mime']) {
				case 'image/jpeg':
					$this->imagecreatefromfunc = function_exists('imagecreatefromjpeg') ? 'imagecreatefromjpeg' : '';
					$this->imagefunc = function_exists('imagejpeg') ? 'imagejpeg' : '';
					break;
				case 'image/gif':
					$this->imagecreatefromfunc = function_exists('imagecreatefromgif') ? 'imagecreatefromgif' : '';
					$this->imagefunc = function_exists('imagegif') ? 'imagegif' : '';
					break;
				case 'image/png':
					$this->imagecreatefromfunc = function_exists('imagecreatefrompng') ? 'imagecreatefrompng' : '';
					$this->imagefunc = function_exists('imagepng') ? 'imagepng' : '';
					break;
			}
		} else {
			$this->imagecreatefromfunc = $this->imagefunc = TRUE;
		}

		$this->attach['size'] = empty($this->attach['size']) ? @filesize($targetfile) : $this->attach['size'];
		if($this->attachinfo['mime'] == 'image/gif') {
			$fp = fopen($targetfile, 'rb');
			$targetfilecontent = fread($fp, $this->attach['size']);
			fclose($fp);
			$this->animatedgif = strpos($targetfilecontent, 'NETSCAPE2.0') === FALSE ? 0 : 1;
		}
	}

	function Thumb($thumbwidth = "220", $thumbheight = "220", $thumb_ext = ".small.jpg") {
		$thumbstatus = 1;
		$imagelib = 0;
		$thumbquality = 100;
		if($thumbstatus && function_exists('imagecreatetruecolor') && function_exists('imagecopyresampled') && function_exists('imagejpeg')) {
			$imagecreatefromfunc = $this->imagecreatefromfunc;
			$imagefunc = $thumbstatus == 1 ? 'imagejpeg' : $this->imagefunc;
			list($img_w, $img_h) = $this->attachinfo;

			if(!$this->animatedgif && ($img_w >= $thumbwidth || $img_h >= $thumbheight)) {
				if($thumbstatus != 3) {
					$attach_photo = $imagecreatefromfunc($this->targetfile);
					$x_ratio = $thumbwidth / $img_w;
					$y_ratio = $thumbheight / $img_h;

					if(($x_ratio * $img_h) < $thumbheight) {
						$thumb['height'] = ceil($x_ratio * $img_h);
						$thumb['width'] = $thumbwidth;
					} else {
						$thumb['width'] = ceil($y_ratio * $img_w);
						$thumb['height'] = $thumbheight;
					}
					$thumb_ext = (is_null($thumb_ext))?'':$thumb_ext;
					$targetfile = $thumbstatus == 1 ? $this->targetfile.$thumb_ext : $this->targetfile;
					$cx = $img_w;
					$cy = $img_h;
				}

				$thumb_photo = imagecreatetruecolor($thumb['width'], $thumb['height']);
				imageCopyreSampled($thumb_photo, $attach_photo ,0, 0, 0, 0, $thumb['width'], $thumb['height'], $cx, $cy);
				clearstatcache();
				if($this->attachinfo['mime'] == 'image/jpeg') {
					$imagefunc($thumb_photo, $targetfile, $thumbquality);
				} else {
					$imagefunc($thumb_photo, $targetfile);
				}
				$this->attach['thumb'] = $thumbstatus == 1 || $thumbstatus == 3 ? 1 : 0;
			}else{
			    copy($this->srcfile, $this->targetfile.$thumb_ext);
			}
		}
		$this->attach['size'] = filesize($this->targetfile);
	}

}
?>