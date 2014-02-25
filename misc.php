<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
define('CURSCRIPT', 'attachment');
require("libraries/common.inc.php");
require("share.inc.php");
if (isset($_GET['do'])) {
	$do = trim($_GET['do']);
	switch ($do) {
		case "download":
			uses("attachment");
			$attachment = new Attachments();
			if (empty($_GET['aid'])) {
				flash();
			}
			$attach_id = authcode(rawurldecode($_GET['aid']), "DECODE");
			if (empty($attach_id)) {
				flash();
			}
			require(LIB_PATH. "func.download.php");
			require(CLASS_PATH. "js.class.php");
			$filename = rawurlencode($attachment->getAttachFileName($attach_id));
			$filename = $attachment->file_url;
			if(!sendFile($filename))
			{
				exit('Error occured when get files.');
			}else{
				JS::Close();
			}
			break;
		default:
			break;
	}
}
if(empty($_GET['id'])){
	$picture_src = URL.STATICURL. "images/watermark.png";
}
if (isset($_GET['source'])) {
	$file_source = trim($_GET['source']);
	$picture_src = URL.$attachment_url.$file_source;
}
setvar("img_src", $picture_src);
render("attachment");
?>