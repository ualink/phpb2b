<?php 
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2095 $
 */
class Errors
{
	function __construct()
	{
		
	}
	
	function showError($msg, $type = null)
	{
	global $charset;
	$host = pb_getenv('HTTP_HOST');
	$title = $type == 'db' ? 'Database' : 'System';
echo <<<EOT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title>$host - $title Error</title>
	<meta http-equiv="Content-Type" content="text/html; charset={$charset}" />
	<meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE" />
	<style type="text/css">
	<!--
	body { background-color: white; color: black; }
	#container { width: 650px; }
	#message   { width: 650px; color: black; background-color: #FFFFCC; }
	#bodytitle { font: 13pt/15pt verdana, arial, sans-serif; height: 35px; vertical-align: top; }
	.bodytext  { font: 8pt/11pt verdana, arial, sans-serif; }
	.help  { font: 12px verdana, arial, sans-serif; color: red;}
	.red  {color: red;}
	a:link     { font: 8pt/11pt verdana, arial, sans-serif; color: red; }
	a:visited  { font: 8pt/11pt verdana, arial, sans-serif; color: #4e4e4e; }
	-->
	</style>
</head>
<body>
<table cellpadding="1" cellspacing="5" id="container">
<tr>
	<td id="bodytitle" width="100%">{$host} $title Error </td>
</tr>
EOT;

		if($type == 'db') {
			$helplink = "http://support.phpb2b.com/";
			echo <<<EOT
<tr>
	<td class="bodytext">The database has encountered a problem. <a href="$helplink" target="_blank"><span class="red">Need Help?</span></a></td>
</tr>
EOT;
		} else {
			echo <<<EOT
<tr>
	<td class="bodytext">Your request has encountered a problem. </td>
</tr>
EOT;
		}

		echo <<<EOT
<tr><td><hr size="1"/></td></tr>
<tr><td class="bodytext">Error messages: </td></tr>
<tr>
	<td class="bodytext" id="message">
		<ul> $msg</ul>
	</td>
</tr>
EOT;
		exit;
}
}